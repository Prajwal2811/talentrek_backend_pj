<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});


Route::post('/subscriptions/payment', [SubscriptionController::class, 'processSubscriptionPayment'])->name('subscription.payment');
Route::any('/subscriptions/success', [SubscriptionController::class, 'successSubscription'])->name('subscription.success');
Route::any('/subscriptions/failure', [SubscriptionController::class, 'failureSubscription'])->name('subscription.failure');









