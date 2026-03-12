<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
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
            // Sanitize input to prevent prompt injection or XSS
            $userMessage = htmlspecialchars(strip_tags($request->input('message')), ENT_QUOTES, 'UTF-8');
            $history = $request->input('history', []);
            $maxTokens = (int) $request->input('max_tokens', 500);

            $response = $this->chatbotService->processChat($userMessage, $history, $maxTokens);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());

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
            'recommendations' => $this->chatbotService->getRecommendations()
        ]);
    }
}
