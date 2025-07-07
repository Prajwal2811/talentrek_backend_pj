<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/', function () {
    return view('site.index');
})->name('home');



Route::get('training', function () {
    return view('site.training');
})->name('training');


Route::get('training-detail', function () {
    return view('site.training-detail');
})->name('training-detail');


Route::get('buy-course', function () {
    return view('site.buy-course');
})->name('buy-course');


Route::get('buy-course-for-team', function () {
    return view('site.buy-course-for-team');
})->name('buy-course-for-team');


Route::get('mentorship', function () {
    return view('site.mentorship');
})->name('mentorship');


Route::get('mentorship-details', function () {
    return view('site.mentorship-details');
})->name('mentorship-details');


Route::get('mentorship-book-session', function () {
    return view('site.mentorship-book-session');
})->name('mentorship-book-session');


Route::get('mentorship-booking-success', function () {
    return view('site.mentorship-booking-success');
})->name('mentorship-booking-success');

Route::get('assessment', function () {
    return view('site.assessment');
})->name('assessment');

Route::get('assessment-details', function () {
    return view('site.assessment-details');
})->name('assessment-details');

Route::get('assessment-book-session', function () {
    return view('site.assessment-book-session');
})->name('assessment-book-session');


Route::get('assessment-booking-success', function () {
    return view('site.assessment-booking-success');
})->name('assessment-booking-success');


Route::get('coaching', function () {
    return view('site.coaching');
})->name('coaching');


Route::get('coach-details', function () {
    return view('site.coach-details');
})->name('coach-details');

Route::get('coach-book-session', function () {
    return view('site.coach-book-session');
})->name('coach-book-session');

Route::get('coach-booking-success', function () {
    return view('site.coach-booking-success');
})->name('coach-booking-success');



Auth::routes();


















