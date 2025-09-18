<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\Auth\GoogleAuthController;

// Redirect root to trips index
Route::get('/', function () {
    return redirect()->route('trips.index');
});

// Trip routes
Route::resource('trips', TripController::class);

// Additional trip routes
Route::post('trips/{trip}/status', [TripController::class, 'updateStatus'])->name('trips.update-status');
Route::get('trips/{trip}/preview', [TripController::class, 'preview'])->name('trips.preview');
Route::post('trips/{trip}/duplicate', [TripController::class, 'duplicate'])->name('trips.duplicate');
Route::post('trips/bulk-delete', [TripController::class, 'bulkDelete'])->name('trips.bulk-delete');
Route::post('trips/bulk-duplicate', [TripController::class, 'bulkDuplicate'])->name('trips.bulk-duplicate');

// Google OAuth routes
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::post('logout', [GoogleAuthController::class, 'logout'])->name('logout');
