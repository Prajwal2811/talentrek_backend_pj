<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});


Route::post('/subscriptions/payment', [SubscriptionController::class, 'processSubscriptionPayment'])->name('subscription.payment');
Route::post('/subscription/payment/success', [SubscriptionController::class, 'successSubscription']);
Route::any('/subscriptions/payment/failure', [SubscriptionController::class, 'failureSubscription']);








