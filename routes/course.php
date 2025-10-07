<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoursePurchaseController;

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});



// for jobseekers
Route::post('/purchase-course', [CoursePurchaseController::class, 'processPurchaseCoursePayment'])->name('jobseeker.purchase-course');
Route::post('/course/payment/success', [CoursePurchaseController::class, 'successPurchaseCourse']);
Route::post('/course/payment/failure', [CoursePurchaseController::class, 'failurePurchaseCourse']);



Route::post('/purchase-course-for-team', [CoursePurchaseController::class, 'processPurchaseCoursePaymentForteam'])->name('jobseeker.team-purchase-course-for-team');
Route::post('/team/course/payment/success', [CoursePurchaseController::class, 'successPurchaseCourseForTeam']);
Route::post('/team/course/payment/failure', [CoursePurchaseController::class, 'failurePurchaseCourseForTeam']);


Route::post('/purchase-course-for-cart', [CoursePurchaseController::class, 'processPurchaseCoursePaymentCart'])->name('jobseeker.cart-purchase-course');
Route::post('/cart/course/payment/success', [CoursePurchaseController::class, 'successPurchaseCourseCart']);
Route::post('/cart/course/payment/failure', [CoursePurchaseController::class, 'failurePurchaseCourseCart']);


// for expat
Route::post('/purchase-course', [CoursePurchaseController::class, 'processPurchaseCoursePayment'])->name('expat.purchase-course');
Route::post('/course/payment/success', [CoursePurchaseController::class, 'successPurchaseCourse']);
Route::post('/course/payment/failure', [CoursePurchaseController::class, 'failurePurchaseCourse']);



Route::post('/purchase-course-for-team', [CoursePurchaseController::class, 'processPurchaseCoursePaymentForteam'])->name('expat.team-purchase-course-for-team');
Route::post('/team/course/payment/success', [CoursePurchaseController::class, 'successPurchaseCourseForTeam']);
Route::post('/team/course/payment/failure', [CoursePurchaseController::class, 'failurePurchaseCourseForTeam']);


Route::post('/purchase-course-for-cart', [CoursePurchaseController::class, 'processPurchaseCoursePaymentCart'])->name('expat.cart-purchase-course');
Route::post('/cart/course/payment/success', [CoursePurchaseController::class, 'successPurchaseCourseCart']);
Route::post('/cart/course/payment/failure', [CoursePurchaseController::class, 'failurePurchaseCourseCart']);





