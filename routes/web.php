<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Google OAuth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});

// Logout route (authenticated users only)
Route::middleware('auth')->post('logout', [GoogleAuthController::class, 'logout'])->name('logout');

// Redirect root to trips index
Route::get('/', function () {
    return redirect()->route('trips.index');
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Trip routes
    Route::resource('trips', TripController::class);

    // Additional trip routes
    Route::post('trips/{trip}/status', [TripController::class, 'updateStatus'])->name('trips.update-status');
    Route::post('trips/{trip}/duplicate', [TripController::class, 'duplicate'])->name('trips.duplicate');
    Route::post('trips/{trip}/generate-share-token', [TripController::class, 'generateShareToken'])->name('trips.generate-share-token');
    Route::get('trips/{trip}/pdf', [TripController::class, 'generatePdf'])->name('trips.pdf')->withoutMiddleware('auth');
    Route::post('trips/bulk-delete', [TripController::class, 'bulkDelete'])->name('trips.bulk-delete');
    Route::post('trips/bulk-duplicate', [TripController::class, 'bulkDuplicate'])->name('trips.bulk-duplicate');
});

// Public preview route (no authentication required)
Route::get('trips/{trip}/preview', [TripController::class, 'preview'])->name('trips.preview');

// Shared trip preview route (no authentication required)
Route::get('trips/share/{token}', [TripController::class, 'share'])->name('trips.share');
