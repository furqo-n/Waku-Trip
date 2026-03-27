<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Booking;
use App\Models\TripSchedule;
use App\Services\BookingService;
use App\Services\MidtransService;
use App\Services\CurrencyService;
use App\Services\VoucherService;
use App\Services\VoucherCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PaymentController extends Controller
{
    protected $midtransService;
    protected $currencyService;
    protected $voucherService;
    protected $voucherCalculatorService;

    public function __construct(MidtransService $midtransService, CurrencyService $currencyService, VoucherService $voucherService, VoucherCalculatorService $voucherCalculatorService)
    {
        $this->midtransService = $midtransService;
        $this->currencyService = $currencyService;
        $this->voucherService = $voucherService;
        $this->voucherCalculatorService = $voucherCalculatorService;
    }
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

    public function showPayment(Request $request): View|RedirectResponse
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect('/')->with('error', 'Please start your booking first.');
        }
        if (($bookingData['user_id'] ?? null) !== Auth::id()) {
            session()->forget('booking_data');
            return redirect('/')->with('error', 'Session invalid. Please start your booking again.');
        }

        $booking = null;
        $booking = null;
        if (session()->has('active_booking_id')) {
            $existingBooking = Booking::find(session('active_booking_id'));
            
            // Check if the current session data matches the physical record
            // If the destination (schedule_id) or group size (guests) changed, 
            // the old booking record is no longer valid for this new request.
            if ($existingBooking && 
                $existingBooking->trip_schedule_id == $bookingData['schedule_id'] && 
                $existingBooking->pax_count == $bookingData['guests'] &&
                $existingBooking->status == 'pending') {
                $booking = $existingBooking;
            }
        }

        if (!$booking) {
            try {
                // If there's an old pending booking, we might want to "revert" it or just ignore it 
                // but for now creating a new one is safer to ensure correct pricing.
                $booking = BookingService::createBookingFromSession($bookingData);
                session(['active_booking_id' => $booking->id]);
            } catch (\RuntimeException $e) {
                return redirect('/planned_list')->with('error', $e->getMessage());
            }
        }

        $data = BookingService::getPaymentViewData($bookingData);
        $data['booking'] = $booking;

        // Update booking total with voucher discount if applied
        $finalTotal = $data['finalTotal'];
        if ($booking->total_price != $finalTotal) {
            $booking->update(['total_price' => $finalTotal]);
        }

        // Midtrans typically only supports IDR for Snap transactions in Indonesia.
        // We force conversion to IDR here so the amount in the popup matches the expected Rupiah value.
        $idrAmount = $this->currencyService->convert($finalTotal, 'IDR');

        // Midtrans parameters
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code . '-' . time(),
                'gross_amount' => (int) round($idrAmount),
            ],
            'customer_details' => [
                'first_name' => $bookingData['first_name'],
                'last_name' => $bookingData['last_name'],
                'email' => $bookingData['email'],
                'phone' => $bookingData['phone'],
            ],
        ];

        $data['snapToken'] = $this->midtransService->getSnapToken($params);

        return view('payment.pay', $data);
    }

    public function confirmPayment(Request $request): RedirectResponse
    {
        $bookingCode = $request->query('order_id');
        $booking = Booking::where('booking_code', $bookingCode)->first();

        if (!$booking) {
            return redirect('/')->with('error', 'Booking not found.');
        }

        // Apply voucher if stored in session
        if (session()->has('voucher_id') && session()->has('voucher_code')) {
            $voucherId = session('voucher_id');
            $voucherCode = session('voucher_code');
            
            try {
                $result = $this->voucherService->apply(
                    $voucherCode,
                    Auth::id(),
                    $booking->total_price,
                    $booking->id
                );
                
                // Clear voucher session after applying
                session()->forget(['voucher_id', 'voucher_code']);
            } catch (\Exception $e) {
                // Voucher application failed, but continue with booking
                session()->forget(['voucher_id', 'voucher_code']);
            }
        }

        if ($booking->status === 'success') {
            session()->forget(['booking_data', 'active_booking_id']);
            return redirect()->route('mybooking')->with('success', 'Payment successful! Your booking code is ' . $booking->booking_code);
        }

        return redirect()->route('mybooking')->with('info', 'Payment is being processed. Please check your booking status in a moment.');
    }

    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'order_amount' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Please login first.'], 401);
        }

        $result = $this->voucherService->validate($request->code, $user->id, $request->order_amount);

        if (!$result['valid']) {
            return response()->json(['success' => false, 'error' => $result['error']]);
        }

        $voucher = $result['voucher'];
        $discountAmount = $this->voucherCalculatorService->calculate($voucher, $request->order_amount);

        // Store voucher in session for later application
        session([
            'voucher_id' => $voucher->id,
            'voucher_code' => $voucher->code,
        ]);

        return response()->json([
            'success' => true,
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'title' => $voucher->title,
                'type' => $voucher->type,
                'value' => $voucher->value,
                'max_discount' => $voucher->max_discount,
            ],
            'discount_amount' => $discountAmount,
            'final_amount' => $request->order_amount - $discountAmount,
        ]);
    }

    public function removeVoucher(Request $request)
    {
        session()->forget(['voucher_id', 'voucher_code']);

        return response()->json(['success' => true]);
    }

    public function notificationHandler(Request $request)
    {
        $notif = $this->midtransService->notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $booking = Booking::where('booking_code', $order_id)->first();

        if ($booking) {
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $booking->update(['status' => 'pending']);
                    } else {
                        $booking->update(['status' => 'success']);
                    }
                }
            } else if ($transaction == 'settlement') {
                $booking->update(['status' => 'success']);
            } else if ($transaction == 'pending') {
                $booking->update(['status' => 'pending']);
            } else if ($transaction == 'deny') {
                $booking->update(['status' => 'failed']);
                // Revert seats?
                BookingService::revertSeats($booking);
            } else if ($transaction == 'expire') {
                $booking->update(['status' => 'expired']);
                BookingService::revertSeats($booking);
            } else if ($transaction == 'cancel') {
                $booking->update(['status' => 'canceled']);
                BookingService::revertSeats($booking);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
