<?php

namespace Tests\Unit;

use App\Services\BookingService;
use App\Models\TripSchedule;
use PHPUnit\Framework\TestCase;

class BookingServiceTest extends TestCase
{
    public function test_has_enough_seats_returns_true_when_enough_seats(): void
    {
        // Create a mock TripSchedule with 10 available seats
        $schedule = $this->createMock(TripSchedule::class);
        $schedule->method('__get')
                 ->willReturnMap([
                     ['available_seats', 10]
                 ]);

        $this->assertTrue(BookingService::hasEnoughSeats($schedule, 5));
        $this->assertTrue(BookingService::hasEnoughSeats($schedule, 10));
    }

    public function test_has_enough_seats_returns_false_when_not_enough_seats(): void
    {
        // Create a mock TripSchedule with 3 available seats
        $schedule = $this->createMock(TripSchedule::class);
        $schedule->method('__get')
                 ->willReturnMap([
                     ['available_seats', 3]
                 ]);

        $this->assertFalse(BookingService::hasEnoughSeats($schedule, 4));
        $this->assertFalse(BookingService::hasEnoughSeats($schedule, 10));
    }

    public function test_generates_valid_booking_code(): void
    {
        $code = BookingService::generateBookingCode();

        // Should start with WKU-
        $this->assertStringStartsWith('WKU-', $code);
        
        // Should have 3 parts separated by dashes
        $parts = explode('-', $code);
        $this->assertCount(3, $parts);
        
        // Length check (WKU-XXXXXX-YYMMDD)
        $this->assertEquals(17, strlen($code));
    }
}
