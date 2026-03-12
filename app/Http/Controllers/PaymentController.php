<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Booking;
use App\Models\TripSchedule;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function showOrderForm(Request $request): View|RedirectResponse
    {
        $scheduleId = $request->query('schedule_id');
        $guests = $request->query('guests', 1);

        if (!$scheduleId) {
            return redirect('/')->with('error', 'Please select a trip first.');
        }

        $schedule = TripSchedule::with('package')->findOrFail($scheduleId);
        $pricePerPerson = $schedule->price;
        $totalPrice = $pricePerPerson * $guests;
        $ppn = $totalPrice * config('pricing.tax_rate', 0.12);
        $fee = $totalPrice * config('pricing.fee_rate', 0.10);
        $discount = $totalPrice * config('pricing.discount_rate', 0.07);
        $finalTotal = $totalPrice + $ppn + $fee - $discount;

        $durationDays = \Carbon\Carbon::parse($schedule->start_date)->diffInDays(\Carbon\Carbon::parse($schedule->end_date)) + 1;

        return view('payment.order', compact('schedule', 'guests', 'pricePerPerson', 'totalPrice', 'durationDays', 'ppn', 'fee', 'discount', 'finalTotal'));
    }

    public function storeOrder(StoreOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to complete your booking.');
        }

        $schedule = TripSchedule::findOrFail($validated['schedule_id']);

        if (!BookingService::hasEnoughSeats($schedule, $validated['guests'])) {
            return back()->withErrors(['guests' => 'Not enough available seats. Only ' . $schedule->available_seats . ' seats left.'])->withInput();
        }

        $bookingData = array_merge($validated, ['user_id' => $user->id]);
        $request->session()->put('booking_data', $bookingData);

        return redirect('/pay');
    }

    public function showPayment(): View|RedirectResponse
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect('/')->with('error', 'Please start your booking first.');
        }
        if (($bookingData['user_id'] ?? null) !== Auth::id()) {
            session()->forget('booking_data');
            return redirect('/')->with('error', 'Session invalid. Please start your booking again.');
        }

        $data = BookingService::getPaymentViewData($bookingData);

        return view('payment.pay', $data);
    }

    public function confirmPayment(Request $request): RedirectResponse
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect('/')->with('error', 'Session expired. Please start your booking again.');
        }
        if (($bookingData['user_id'] ?? null) !== Auth::id()) {
            session()->forget('booking_data');
            return redirect('/')->with('error', 'Session invalid. Please start your booking again.');
        }

        $schedule = TripSchedule::findOrFail($bookingData['schedule_id']);
        if (!BookingService::hasEnoughSeats($schedule, $bookingData['guests'])) {
            return redirect('/planned_list')->with('error', 'Sorry, seats are no longer available.');
        }

        try {
            $booking = BookingService::createBookingFromSession($bookingData);
            $request->session()->forget('booking_data');
            return redirect()->route('mybooking')->with('success', 'Booking confirmed! Your booking code is ' . $booking->booking_code);
        } catch (\RuntimeException $e) {
            return redirect('/planned_list')->with('error', $e->getMessage());
        }
    }
}
