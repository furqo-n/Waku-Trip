<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\TripSchedule;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    public static function hasEnoughSeats(TripSchedule $schedule, int $guests): bool
    {
        return $schedule->available_seats >= $guests;
    }

    public static function generateBookingCode(): string
    {
        return 'WKU-' . strtoupper(substr(uniqid(), -6)) . '-' . date('ymd');
    }

    public static function createBookingFromSession(array $bookingData): Booking
    {
        $schedule = TripSchedule::findOrFail($bookingData['schedule_id']);
        $user = Auth::user();

        if (!self::hasEnoughSeats($schedule, $bookingData['guests'])) {
            throw new \RuntimeException('Not enough available seats.');
        }

        $basePrice = $schedule->price * $bookingData['guests'];
        $ppn = $basePrice * config('pricing.tax_rate', 0.12);
        $fee = $basePrice * config('pricing.fee_rate', 0.10);
        $discount = $basePrice * config('pricing.discount_rate', 0.07);
        $totalPrice = $basePrice + $ppn + $fee - $discount;

        $bookingCode = self::generateBookingCode();

        $booking = Booking::create([
            'user_id' => $user->id,
            'trip_schedule_id' => $schedule->id,
            'booking_code' => $bookingCode,
            'pax_count' => $bookingData['guests'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'special_requests' => $bookingData['special_requests'] ?? null,
        ]);

        BookingPassenger::create([
            'booking_id' => $booking->id,
            'name' => $bookingData['first_name'] . ' ' . $bookingData['last_name'],
            'passport_number' => $bookingData['passport_number'] ?? null,
            'date_of_birth' => $bookingData['date_of_birth'] ?? null,
            'gender' => $bookingData['gender'] ?? null,
        ]);

        $schedule->decrement('available_seats', $bookingData['guests']);

        if ($schedule->fresh()->available_seats <= 0) {
            $schedule->update(['status' => 'full']);
        }

        return $booking;
    }

    public static function getPaymentViewData(array $bookingData): array
    {
        $schedule = TripSchedule::with('package.media')->findOrFail($bookingData['schedule_id']);
        $guests = $bookingData['guests'];
        $pricePerPerson = $schedule->price;
        $basePrice = $pricePerPerson * $guests;
        $ppn = $basePrice * config('pricing.tax_rate', 0.12);
        $fee = $basePrice * config('pricing.fee_rate', 0.10);
        $discount = $basePrice * config('pricing.discount_rate', 0.07);
        $totalPrice = $basePrice + $ppn + $fee - $discount;

        $durationDays = \Carbon\Carbon::parse($schedule->start_date)->diffInDays(\Carbon\Carbon::parse($schedule->end_date)) + 1;
        $travelerName = $bookingData['first_name'] . ' ' . $bookingData['last_name'];
        $travelerEmail = $bookingData['email'];
        $travelerPhone = $bookingData['phone_code'] . ' ' . $bookingData['phone'];

        $imageUrl = $schedule->package->primary_image_url;

        return [
            'schedule' => $schedule,
            'guests' => $guests,
            'pricePerPerson' => $pricePerPerson,
            'totalPrice' => $totalPrice,
            'basePrice' => $basePrice,
            'ppn' => $ppn,
            'fee' => $fee,
            'discount' => $discount,
            'durationDays' => $durationDays,
            'travelerName' => $travelerName,
            'travelerEmail' => $travelerEmail,
            'travelerPhone' => $travelerPhone,
            'imageUrl' => $imageUrl,
        ];
    }
    public static function revertSeats(Booking $booking): void
    {
        $schedule = $booking->tripSchedule;
        $schedule->increment('available_seats', $booking->pax_count);
        
        if ($schedule->fresh()->available_seats > 0 && $schedule->status === 'full') {
            $schedule->update(['status' => 'scheduled']); // or whatever the default status is
        }
    }
}
