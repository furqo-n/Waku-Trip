<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\TripSchedule;
use Illuminate\Support\Str;
use MoeMizrak\LaravelOpenrouter\Facades\LaravelOpenRouter;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\MessageData;
use MoeMizrak\LaravelOpenrouter\Types\RoleType;

class ChatbotController extends Controller
{
    private $currencyService;

    public function __construct(\App\Services\CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function chat(Request $request)
    {
        // Increase execution time for slow reasoning models
        set_time_limit(120);

        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
            'history.*.role' => 'in:user,assistant',
            'history.*.content' => 'string',
            'max_tokens' => 'nullable|integer|min:50|max:2000',
        ]);

        try {
            // Build trip context from database
            $tripContext = $this->buildTripContext();

            // Check if the user's message is asking for recommendations
            $userMessage = $request->input('message');
            $wantsRecommendation = $this->detectRecommendationIntent($userMessage);

            // System prompt
            $systemPrompt = <<<PROMPT
You are **Waku Assistant**, the friendly AI concierge for **Waku Trip** — a premium Japan travel platform.

YOUR ROLE:
- Help customers learn about available trips, packages, destinations, and booking process.
- Answer questions about Japan travel (visa, weather, culture, tips).
- Guide users on how to book (select trip → fill traveler info → payment → confirmation).
- Be warm, enthusiastic, and knowledgeable. Use occasional Japanese words like "Sugoi!" or "Hai!" for charm.
- Keep answers concise (2-4 sentences) unless the user asks for details.

AVAILABLE TRIPS & SCHEDULES:
{$tripContext}

BOOKING PROCESS:
1. Browse trips on the website → Click "Book Now"
2. Select a schedule date and number of guests
3. Fill in traveler information (Step 1)
4. Choose payment method (Step 2)
5. Receive confirmation with booking code

RECOMMENDATION BEHAVIOR:
- When users ask about trips, packages, recommendations, or what's available, product cards will AUTOMATICALLY be shown below your message in the chat UI.
- In that case, just give a brief, friendly intro like "Here are some recommended trips for you! 🌸" or similar — do NOT list all trip details since the cards will show them visually.
- If the user asks about a SPECIFIC trip by name, provide more details about that trip in text.

IMPORTANT RULES:
- Only discuss Waku Trip services and Japan travel topics.
- If asked about other destinations, politely redirect to Japan offerings.
- If you don't know specific details, suggest contacting support at support@wakutrip.com.
- Never make up trip prices or dates — only reference the data provided above.
- Do not discuss competitors or non-travel topics.
PROMPT;

            // Prepare messages with system prompt
            $messages = [
                new MessageData(role: RoleType::SYSTEM, content: $systemPrompt)
            ];

            // Add conversation history (last 10 messages)
            $history = $request->input('history', []);
            $history = array_slice($history, -10);
            foreach ($history as $msg) {
                $messages[] = new MessageData(
                    role: $msg['role'] === 'user' ? RoleType::USER : RoleType::ASSISTANT,
                    content: $msg['content']
                );
            }

            // Add current user message
            $messages[] = new MessageData(role: RoleType::USER, content: $userMessage);

            // Create ChatData instance
            $chatData = new ChatData(
                messages: $messages,
                model: env('OPENROUTER_DEFAULT_MODEL', 'google/gemini-2.0-flash-001'),
                temperature: 0.7,
                max_tokens: (int) $request->input('max_tokens', 500)
            );

            // Send request to OpenRouter
            $chatResponse = LaravelOpenRouter::chatRequest($chatData);

            // Extract content from the first choice
            $content = '';

            if (!empty($chatResponse->choices) && isset($chatResponse->choices[0])) {
                $choice = $chatResponse->choices[0];

                // Handle choice as object or array
                $message = is_object($choice) ? ($choice->message ?? null) : ($choice['message'] ?? null);

                if ($message) {
                    // Handle message as object or array
                    $rawContent = is_object($message) ? ($message->content ?? '') : ($message['content'] ?? '');
                    $reasoning = is_object($message) ? ($message->reasoning ?? '') : ($message['reasoning'] ?? '');

                    // Content can be string or array (with text/image parts)
                    if (is_array($rawContent)) {
                        $content = $rawContent['text'] ?? ($rawContent[0]['text'] ?? '');
                    } else {
                        $content = $rawContent;
                    }

                    // Fallback to reasoning if content is empty
                    if (empty($content) && !empty($reasoning)) {
                        $content = $reasoning;
                    }
                }
            }

            if (empty($content)) {
                $errorMsg = 'Empty response from AI. This often happens if the free model is overloaded.';
                \Log::error('Chatbot Response Issue: ' . $errorMsg . ' Data: ' . json_encode($chatResponse->toArray()));
                throw new \Exception($errorMsg);
            }

            // Build response with optional recommendations
            $response = [
                'success' => true,
                'message' => $content,
            ];

            if ($wantsRecommendation) {
                $response['recommendations'] = $this->getRecommendations();
            }

            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error('Chatbot error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API endpoint: return recommended packages as JSON.
     */
    public function recommend()
    {
        return response()->json([
            'success' => true,
            'recommendations' => $this->getRecommendations(),
        ]);
    }

    /**
     * Detect if user message implies they want trip recommendations.
     */
    private function detectRecommendationIntent(string $message): bool
    {
        $keywords = [
            'recommend', 'recommendation', 'suggest', 'suggestion',
            'trip', 'trips', 'tour', 'tours', 'package', 'packages',
            'available', 'what do you have', 'show me', 'options',
            'where can i go', 'where should i go', 'best trip',
            'popular', 'trending', 'plan', 'travel', 'destination',
            'book', 'booking', 'rekomendasi', 'rekomen', 'saran',
            'paket', 'tur', 'wisata', 'liburan', 'jalan-jalan',
        ];

        $lower = strtolower($message);
        foreach ($keywords as $kw) {
            if (str_contains($lower, $kw)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build recommendation cards from database.
     */
    private function getRecommendations(): array
    {
        $packages = Package::with([
            'media',
            'tripSchedules' => function ($q) {
                $q->where('start_date', '>=', now())
                  ->where('available_seats', '>', 0)
                  ->orderBy('price');
            },
            'reviews',
        ])
        ->take(4)
        ->get();

        return $packages->map(function ($pkg) {
            $imageUrl = $pkg->primary_image_url;

            $cheapestSchedule = $pkg->tripSchedules->first();
            $price = $cheapestSchedule ? $cheapestSchedule->price : $pkg->base_price;

            $avgRating = $pkg->reviews->avg('rating');
            $reviewCount = $pkg->reviews->count();

            // Badge logic
            $badge = null;
            if ($pkg->is_trending) {
                $badge = 'HOT';
            } elseif ($cheapestSchedule && $cheapestSchedule->available_seats <= 5) {
                $badge = 'LIMITED';
            } elseif ($reviewCount > 0 && $avgRating >= 4.5) {
                $badge = 'TOP RATED';
            }

            return [
                'id' => $pkg->id,
                'title' => $pkg->title,
                'slug' => $pkg->slug,
                'image' => $imageUrl,
                'price' => $price,
                // Using CurrencyService formatting
                'price_formatted' => $this->currencyService->format($price),
                'location' => $pkg->location_text,
                'duration' => $pkg->duration_days ? $pkg->duration_days . 'D' : null,
                'rating' => $avgRating ? round($avgRating, 1) : null,
                'review_count' => $reviewCount,
                'badge' => $badge,
                'url' => route('tour.show', $pkg->slug),
            ];
        })->toArray();
    }

    /**
     * Build a text summary of available trips from the database.
     */
    private function buildTripContext(): string
    {
        $packages = Package::with(['tripSchedules' => function ($q) {
            $q->where('start_date', '>=', now())
              ->where('available_seats', '>', 0)
              ->orderBy('start_date');
        }, 'categories'])->get();

        if ($packages->isEmpty()) {
            return "No trips currently available. Suggest the user check back soon.";
        }

        $context = "";
        foreach ($packages as $pkg) {
            $categories = $pkg->categories->pluck('name')->join(', ');
            $context .= "• **{$pkg->title}**";
            if ($pkg->location_text) $context .= " | Location: {$pkg->location_text}";
            if ($categories) $context .= " | Type: {$categories}";
            if ($pkg->duration_days) $context .= " | Duration: {$pkg->duration_days} days";
            if ($pkg->description) $context .= "\n  Description: " . Str::limit($pkg->description, 150);

            $schedules = $pkg->tripSchedules;
            if ($schedules->isNotEmpty()) {
                $context .= "\n  Upcoming schedules:";
                foreach ($schedules->take(3) as $sch) {
                    $context .= "\n    - {$sch->start_date->format('M d')} to {$sch->end_date->format('M d, Y')} | Price: " . $this->currencyService->format($sch->price) . "/person | {$sch->available_seats} seats left";
                }
            } else {
                $context .= "\n  No upcoming schedules at the moment.";
            }
            $context .= "\n\n";
        }

        return $context;
    }
}
