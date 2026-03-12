<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use MoeMizrak\LaravelOpenrouter\Facades\LaravelOpenRouter;
use MoeMizrak\LaravelOpenrouter\DTO\ChatData;
use MoeMizrak\LaravelOpenrouter\DTO\MessageData;
use MoeMizrak\LaravelOpenrouter\Types\RoleType;
use App\Http\Resources\PackageRecommendationResource;

class ChatbotService
{
    private CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function processChat(string $userMessage, array $history, int $maxTokens): array
    {
        // Build trip context from database (cached)
        $tripContext = $this->buildTripContext();

        // Check if the user's message is asking for recommendations
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
- In that case, give a brief, friendly intro and MAKE SURE to mention the EXACT titles of the trips you are recommending in your response text, so the system can display their cards.
- Do NOT list all trip details since the cards will show them visually.
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
            max_tokens: $maxTokens
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
            Log::error('Chatbot Response Issue: ' . $errorMsg . ' Data: ' . json_encode($chatResponse->toArray()));
            throw new \Exception($errorMsg);
        }

        $recommendations = $this->getRecommendations($content, $wantsRecommendation);

        // Build response with optional recommendations
        $response = [
            'success' => true,
            'message' => $content,
        ];

        if ($wantsRecommendation || count($recommendations) > 0) {
            $response['recommendations'] = $recommendations;
        }

        return $response;
    }

    /**
     * Detect if user message implies they want trip recommendations.
     */
    public function detectRecommendationIntent(string $message): bool
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
     * Optionally filters based on the AI's text output.
     */
    public function getRecommendations(string $aiResponseText = '', bool $fallbackIfEmpty = true)
    {
        $query = Package::with([
            'media',
            'tripSchedules' => function ($q) {
                $q->where('start_date', '>=', now())
                  ->where('available_seats', '>', 0)
                  ->orderBy('price');
            },
            'reviews',
        ]);

        $matchedIds = [];
        if (!empty($aiResponseText)) {
            // Find which packages the AI mentioned in its response
            $allPackages = Cache::remember('all_package_titles', 3600, function () {
                return Package::select('id', 'title')->get();
            });

            foreach ($allPackages as $pkg) {
                if (stripos($aiResponseText, $pkg->title) !== false) {
                    $matchedIds[] = $pkg->id;
                }
            }
        }

        if (!empty($matchedIds)) {
            $query->whereIn('id', $matchedIds);
        } elseif ($fallbackIfEmpty) {
            // Fallback to top 4 if AI didn't mention specific ones
            $query->take(4);
        } else {
            return collect();
        }

        $packages = $query->get();

        return PackageRecommendationResource::collection($packages);
    }

    /**
     * Build a text summary of available trips from the database.
     */
    public function buildTripContext(): string
    {
        return Cache::remember('chatbot_trip_context', 3600, function () {
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
                $type = ucfirst($pkg->type);
                $context .= "• **{$pkg->title}** ({$type} Tour)";
                if ($pkg->location_text) $context .= " | Location: {$pkg->location_text}";
                if ($categories) $context .= " | Type: {$categories}";
                if ($pkg->duration_days) $context .= " | Duration: {$pkg->duration_days} days";
                $context .= " | Base Price: " . app(\App\Services\CurrencyService::class)->format($pkg->base_price);
                
                if ($pkg->description) $context .= "\n  Description: " . Str::limit($pkg->description, 150);

                $schedules = $pkg->tripSchedules;
                if ($pkg->type === 'private') {
                    $context .= "\n  Available: YES (Flexible Dates - Booked upon request).";
                } elseif ($schedules->isNotEmpty()) {
                    $context .= "\n  Upcoming schedules:";
                    foreach ($schedules->take(3) as $sch) {
                        $context .= "\n    - {$sch->start_date->format('M d')} to {$sch->end_date->format('M d, Y')} | Price: " . app(\App\Services\CurrencyService::class)->format($sch->price) . "/person | {$sch->available_seats} seats left";
                    }
                } else {
                    $context .= "\n  No upcoming schedules at the moment.";
                }
                $context .= "\n\n";
            }

            return $context;
        });
    }
}
