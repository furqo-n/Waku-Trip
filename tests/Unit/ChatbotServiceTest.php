<?php

namespace Tests\Unit;

use App\Services\ChatbotService;
use App\Services\CurrencyService;
use PHPUnit\Framework\TestCase;

class ChatbotServiceTest extends TestCase
{
    private ChatbotService $chatbotService;

    protected function setUp(): void
    {
        parent::setUp();
        // Use a mock CurrencyService since we don't need real formatting for this test
        $currencyServiceMock = $this->createMock(CurrencyService::class);
        $this->chatbotService = new ChatbotService($currencyServiceMock);
    }

    public function test_detects_recommendation_intent_correctly(): void
    {
        // Positive cases
        $this->assertTrue($this->chatbotService->detectRecommendationIntent("Can you recommend a trip?"));
        $this->assertTrue($this->chatbotService->detectRecommendationIntent("I want to book a tour."));
        $this->assertTrue($this->chatbotService->detectRecommendationIntent("What options are available?"));
        $this->assertTrue($this->chatbotService->detectRecommendationIntent("Ada rekomendasi paket liburan?"));
        
        // Negative cases
        $this->assertFalse($this->chatbotService->detectRecommendationIntent("Hello"));
        $this->assertFalse($this->chatbotService->detectRecommendationIntent("Thank you"));
        $this->assertFalse($this->chatbotService->detectRecommendationIntent("Do I need a visa?"));
        $this->assertFalse($this->chatbotService->detectRecommendationIntent("What is the weather like in Tokyo?"));
    }
}
