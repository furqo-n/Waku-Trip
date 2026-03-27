<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuestRequest;
use App\Models\Booking;
use App\Models\BookingPassenger;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $baseQuery = fn () => Booking::where('user_id', $user->id)
            ->with(['tripSchedule.package.media', 'passengers']);

        $upcomingBookings = $baseQuery()
            ->whereIn('status', ['confirmed', 'pending', 'paid'])
            ->whereHas('tripSchedule', fn ($q) => $q->where('start_date', '>=', now()))
            ->orderBy('created_at', 'desc')
            ->get();

        $pastBookings = $baseQuery()
            ->where(function ($q) {
                $q->where('status', 'completed')
                  ->orWhereHas('tripSchedule', fn ($sq) => $sq->where('end_date', '<', now()));
            })
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        $cancelledBookings = $baseQuery()
            ->where('status', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.mybooking', compact('upcomingBookings', 'pastBookings', 'cancelledBookings'));
    }

    public function manage(string $id): View
    {
        $booking = Booking::with([
            'passengers',
            'payment',
            'tripSchedule.package.media',
            'tripSchedule.package.itineraries',
            'tripSchedule.package.inclusions',
        ])
            ->where('id', $id)
            ->firstOrFail();

        Gate::authorize('view', $booking);

        return view('profile.managebook', compact('booking'));
    }

    public function receipt(string $id): View
    {
        $booking = Booking::with([
            'passengers',
            'payment',
            'tripSchedule.package.media',
            'tripSchedule.package.inclusions',
            'user'
        ])
            ->where('id', $id)
            ->firstOrFail();

        Gate::authorize('view', $booking);

        $schedule = $booking->tripSchedule;
        $pricePerPerson = $schedule->price;
        $basePrice = $pricePerPerson * $booking->pax_count;
        $ppn = $basePrice * config('pricing.tax_rate', 0.12);
        $fee = $basePrice * config('pricing.fee_rate', 0.10);
        $discount = $basePrice * config('pricing.discount_rate', 0.07);
        $durationDays = Carbon::parse($schedule->start_date)->diffInDays(Carbon::parse($schedule->end_date)) + 1;
        $imageUrl = $schedule->package->primary_image_url;

        return view('profile.receipt', compact(
            'booking', 'schedule', 'pricePerPerson', 'basePrice',
            'ppn', 'fee', 'discount', 'durationDays', 'imageUrl'
        ));
    }

    public function cancel(string $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        Gate::authorize('cancel', $booking);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->update(['status' => 'cancelled']);
        $booking->tripSchedule->increment('available_seats', $booking->pax_count);

        $schedule = $booking->tripSchedule->fresh();
        if ($schedule->available_seats > 0 && $schedule->status === 'full') {
            $schedule->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking ' . $booking->booking_code . ' has been cancelled.');
    }

    public function pay(string $id): RedirectResponse
    {
        $booking = Booking::where('status', 'pending')->findOrFail($id);
        Gate::authorize('pay', $booking);

        $booking->update(['status' => 'paid']);

        session()->put('paid_booking', [
            'booking_code' => $booking->booking_code,
            'booking_id' => $booking->id,
        ]);

        return redirect('/paid');
    }

    public function paid(): View|RedirectResponse
    {
        $paidData = session('paid_booking');
        if (!$paidData) {
            return redirect('/mybooking')->with('error', 'No recent payment found.');
        }

        $booking = Booking::with(['tripSchedule.package.media', 'passengers'])
            ->findOrFail($paidData['booking_id']);

        $schedule = $booking->tripSchedule;
        $package = $schedule->package;
        $imageUrl = $package->primary_image_url;
        $durationDays = Carbon::parse($schedule->start_date)->diffInDays(Carbon::parse($schedule->end_date)) + 1;

        session()->forget('paid_booking');

        return view('payment.paid', compact('booking', 'schedule', 'package', 'imageUrl', 'durationDays'));
    }

    public function guests(string $id): View
    {
        $booking = Booking::with(['passengers', 'tripSchedule.package'])
            ->findOrFail($id);

        Gate::authorize('manageGuests', $booking);

        return view('profile.addguest', compact('booking'));
    }

    public function storeGuest(StoreGuestRequest $request, string $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        Gate::authorize('manageGuests', $booking);

        if ($booking->passengers()->count() >= $booking->pax_count) {
            return back()->with('error', 'Maximum number of guests reached for this booking.');
        }

        $booking->passengers()->create($request->validated());

        return back()->with('success', 'Guest added successfully.');
    }

    public function destroyGuest(string $guestId): RedirectResponse
    {
        $guest = BookingPassenger::with('booking')->findOrFail($guestId);
        Gate::authorize('delete', $guest);

        $guest->delete();

        return back()->with('success', 'Guest removed.');
    }

    public function downloadTicket(string $bookingId, string $passengerId): View|RedirectResponse
    {
        $booking = Booking::with([
            'passengers',
            'tripSchedule.package.media',
        ])->findOrFail($bookingId);

        Gate::authorize('view', $booking);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Tickets are only available for confirmed bookings.');
        }

        $passenger = $booking->passengers()->findOrFail($passengerId);

        // Determine passenger index for ticket number
        $passengerIndex = $booking->passengers->search(fn($p) => $p->id === $passenger->id);

        return view('profile.ticket', compact('booking', 'passenger', 'passengerIndex'));
    }

    public function downloadAllTickets(string $bookingId): View|RedirectResponse
    {
        $booking = Booking::with([
            'passengers',
            'tripSchedule.package.media',
        ])->findOrFail($bookingId);

        Gate::authorize('view', $booking);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Tickets are only available for confirmed bookings.');
        }

        $passengers = $booking->passengers;

        return view('profile.ticket', compact('booking', 'passengers'));
    }
}
