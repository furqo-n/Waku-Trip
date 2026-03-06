<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardsController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/planned_list', [App\Http\Controllers\PlannedTripController::class, 'index'])->name('planned.index');
Route::get('/private_list', [App\Http\Controllers\PlannedTripController::class, 'privateList'])->name('private.list');
Route::get('/tour/{slug}', [App\Http\Controllers\TourController::class, 'show'])->name('tour.show');

Route::get('/aboutus', [PageController::class, 'aboutUs'])->name('aboutus');
Route::get('/custome', [PageController::class, 'custome'])->name('custome');
Route::get('/japan-map', [PageController::class, 'japanMap'])->name('japan-map');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/signup', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/signup', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rewards', [RewardsController::class, 'index'])->name('rewards');
    Route::post('/rewards/redeem', [RewardsController::class, 'redeem'])->name('rewards.redeem');
    Route::get('/mybooking', [BookingController::class, 'index'])->name('mybooking');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    Route::post('/booking/{id}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/{id}/pay', [BookingController::class, 'pay'])->name('booking.pay');
    Route::get('/paid', [BookingController::class, 'paid'])->name('booking.paid');
    Route::get('/booking/{id}/manage', [BookingController::class, 'manage'])->name('booking.manage');
    Route::get('/booking/{id}/guests', [BookingController::class, 'guests'])->name('booking.guests');
    Route::post('/booking/{id}/guests', [BookingController::class, 'storeGuest'])->name('booking.guests.store');
    Route::delete('/booking/guests/{guestId}', [BookingController::class, 'destroyGuest'])->name('booking.guests.destroy');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::post('/news/{slug}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

Route::get('/order', [PaymentController::class, 'showOrderForm'])->name('order.form');
Route::post('/order', [PaymentController::class, 'storeOrder'])->name('order.store')->middleware('auth');
Route::get('/pay', [PaymentController::class, 'showPayment'])->name('pay.form')->middleware('auth');
Route::post('/pay', [PaymentController::class, 'confirmPayment'])->name('pay.confirm')->middleware('auth');

Route::post('/chatbot', [App\Http\Controllers\ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::get('/chatbot/recommend', [App\Http\Controllers\ChatbotController::class, 'recommend'])->name('chatbot.recommend');
Route::post('/currency/switch', [App\Http\Controllers\CurrencyController::class, 'switch'])->name('currency.switch');
