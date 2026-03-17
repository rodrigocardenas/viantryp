<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TripController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\AirlineController;
use App\Http\Controllers\GooglePlacesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\AirportController;

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class , 'login']);
    Route::get('/register', [RegisterController::class , 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class , 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class , 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class , 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class , 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class , 'resetPassword'])->name('password.update');
});

// Google OAuth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('auth/google', [GoogleAuthController::class , 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleAuthController::class , 'handleGoogleCallback']);
});

// Logout route (authenticated users only)
Route::middleware('auth')->post('logout', [GoogleAuthController::class , 'logout'])->name('logout');

// Public landing page
Route::get('/', function () {
    return view('landing');
})->name('home');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Trip routes
    Route::get('trips/create-pro', [TripController::class , 'createPro'])->name('trips.create-pro');
    Route::post('trips/store-pro', [TripController::class , 'storePro'])->name('trips.store-pro');
    Route::resource('trips', TripController::class)->only(['index', 'edit', 'destroy']);

    // Person routes
    Route::resource('persons', PersonController::class);
    Route::get('persons-agents', [PersonController::class , 'getAgents'])->name('persons.agents');

    // Airline routes
    Route::resource('airlines', AirlineController::class);
    Route::get('api/airlines', [AirlineController::class , 'apiIndex'])->name('api.airlines.index');
    Route::get('api/airports', [AirportController::class , 'apiIndex'])->name('api.airports.index');
    Route::get('api/unsplash/search', [TripController::class , 'searchUnsplash'])->name('api.unsplash.search');

    // Additional trip routes
    Route::post('trips/{trip}/status', [TripController::class , 'updateStatus'])->name('trips.update-status');
    Route::post('trips/{trip}/code', [TripController::class , 'updateCode'])->name('trips.update-code');
    Route::post('trips/{trip}/inline-update', [TripController::class , 'inlineUpdate'])->name('trips.inline-update');
    Route::post('trips/{trip}/cover', [TripController::class , 'uploadCover'])->name('trips.upload-cover');
    Route::post('trips/{trip}/duplicate', [TripController::class , 'duplicate'])->name('trips.duplicate');
    Route::post('trips/{trip}/generate-share-token', [TripController::class , 'generateShareToken'])->name('trips.generate-share-token');
    Route::get('/trips/{trip}/get-pro-data', [TripController::class, 'getProData'])->name('trips.pro-data');
    Route::post('trips/{trip}/save-pro-state', [TripController::class , 'saveProState'])->name('trips.save-pro-state');
    Route::post('trips/{trip}/upload-attachment', [TripController::class , 'uploadAttachment'])->name('trips.upload-attachment');
    Route::post('trips/{trip}/send-email', [TripController::class , 'sendEmail'])->name('trips.send-email');
    Route::post('trips/bulk-delete', [TripController::class , 'bulkDelete'])->name('trips.bulk-delete');
    Route::post('trips/bulk-duplicate', [TripController::class , 'bulkDuplicate'])->name('trips.bulk-duplicate');
    Route::post('trips/{trip}/invite', [TripController::class , 'inviteCollaborator'])->name('trips.invite');
    Route::post('trips/{trip}/transfer', [TripController::class , 'transferOwnership'])->name('trips.transfer');
    Route::get('trips/accept-invite/{token}', [TripController::class , 'acceptInvite'])->name('trips.accept-invite');

    // Document routes
    Route::delete('documents/{document}', [\App\Http\Controllers\TripDocumentController::class , 'destroy'])->name('documents.destroy');
    Route::get('documents/{document}/download', [\App\Http\Controllers\TripDocumentController::class , 'download'])->name('documents.download')->withoutMiddleware('auth');
    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/personal', [\App\Http\Controllers\ProfileController::class, 'updatePersonal'])->name('profile.update.personal');
    Route::post('/profile/agency', [\App\Http\Controllers\ProfileController::class, 'updateAgency'])->name('profile.update.agency');
    Route::post('/profile/theme', [\App\Http\Controllers\ProfileController::class, 'updateTheme'])->name('profile.update.theme');
    Route::post('/profile/tutorial', [\App\Http\Controllers\ProfileController::class, 'completeTutorial'])->name('profile.complete.tutorial');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'uploadAvatar'])->name('profile.upload.avatar');
    Route::post('/profile/avatar/delete', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.delete.avatar');
    Route::post('/profile/logo', [\App\Http\Controllers\ProfileController::class, 'uploadLogo'])->name('profile.upload.logo');

    Route::get('/notifications', function() {
        return response()->json([
            'notifications' => auth()->user()->notifications()->latest()->take(10)->get(),
            'unread_count' => auth()->user()->unreadNotifications->count()
        ]);
    })->name('notifications.get');

    Route::post('/notifications/mark-read/{id}', function($id) {
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.mark-read-single');

    Route::post('/notifications/mark-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.mark-read');
});

// Google Places API routes (outside auth middleware for AJAX requests)
Route::post('api/places/details', [GooglePlacesController::class , 'getPlaceDetails'])->name('places.details');

// Shared trip preview route (no authentication required)
Route::get('trips/share/{token}', [TripController::class , 'share'])->name('trips.share');
