<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionBookingController;

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});


Route::post('/purchase-session/payment', [SessionBookingController::class, 'processBookingPayment'])->name('session.booking-submit');
Route::post('/purchase-session/payment/success', [SessionBookingController::class, 'successBooking']);
Route::post('/purchase-session/payment/failure', [SessionBookingController::class, 'failureBooking']);





