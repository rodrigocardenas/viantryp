<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\AirlineController;
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

    // Person routes
    Route::resource('persons', PersonController::class);
    Route::get('persons-agents', [PersonController::class, 'getAgents'])->name('persons.agents');

    // Airline routes
    Route::resource('airlines', AirlineController::class);
    Route::get('api/airlines', [AirlineController::class, 'apiIndex'])->name('api.airlines.index');

    // Additional trip routes
    Route::post('trips/{trip}/status', [TripController::class, 'updateStatus'])->name('trips.update-status');
    Route::post('trips/{trip}/code', [TripController::class, 'updateCode'])->name('trips.update-code');
    Route::post('trips/{trip}/cover', [TripController::class, 'uploadCover'])->name('trips.upload-cover');
    Route::post('trips/{trip}/duplicate', [TripController::class, 'duplicate'])->name('trips.duplicate');
    Route::post('trips/{trip}/generate-share-token', [TripController::class, 'generateShareToken'])->name('trips.generate-share-token');
    Route::post('trips/{trip}/send-email', [TripController::class, 'sendEmail'])->name('trips.send-email');
    Route::get('trips/{trip}/pdf', [TripController::class, 'generatePdf'])->name('trips.pdf')->withoutMiddleware('auth');
    Route::post('trips/bulk-delete', [TripController::class, 'bulkDelete'])->name('trips.bulk-delete');
    Route::post('trips/bulk-duplicate', [TripController::class, 'bulkDuplicate'])->name('trips.bulk-duplicate');

    // Document routes
    Route::post('trips/{trip}/documents/upload', [\App\Http\Controllers\TripDocumentController::class, 'upload'])->name('trips.documents.upload');
    Route::post('documents/temp-upload', [\App\Http\Controllers\TripDocumentController::class, 'tempUpload'])->name('documents.temp-upload');
    Route::post('documents/process-temp', [\App\Http\Controllers\TripDocumentController::class, 'processTemp'])->name('documents.process-temp');
    Route::post('trips/{trip}/documents/update-item-id', [\App\Http\Controllers\TripDocumentController::class, 'updateItemId'])->name('trips.documents.update-item-id');
    Route::get('trips/{trip}/documents', [\App\Http\Controllers\TripDocumentController::class, 'getByItem'])->name('trips.documents.get');
    Route::delete('documents/{document}', [\App\Http\Controllers\TripDocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('documents/{document}/download', [\App\Http\Controllers\TripDocumentController::class, 'download'])->name('documents.download');
});

// Public preview route (no authentication required)
Route::get('trips/{trip}/preview', [TripController::class, 'preview'])->name('trips.preview');

// Shared trip preview route (no authentication required)
Route::get('trips/share/{token}', [TripController::class, 'share'])->name('trips.share');
