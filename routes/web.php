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
use App\Http\Controllers\TripAiController;
use App\Http\Controllers\FlightLookupController;
use App\Http\Controllers\ImageProxyController;

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class , 'login']);
    Route::get('/register', [RegisterController::class , 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class , 'register']);

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class , 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class , 'sendResetLink'])->name('password.email')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class , 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class , 'resetPassword'])->name('password.update')->middleware('throttle:5,1');
});

// Google OAuth routes (guest only - browser flow)
Route::middleware('guest')->group(function () {
    Route::get('auth/google', [GoogleAuthController::class , 'redirectToGoogle'])->name('auth.google');
    Route::match(['get', 'post'], 'auth/google/callback', [GoogleAuthController::class , 'handleGoogleCallback']);
});

// Native Capacitor auth endpoints — public (no guest middleware)
// These must be accessible even if the browser still has an old session cookie
// after GoogleAuth.signOut() on the client, because the server session may not
// have been cleared yet when the user taps an account in the account picker.
Route::post('auth/google/native', [GoogleAuthController::class , 'handleNativeGoogleAuth'])->name('auth.google.native')->middleware('throttle:15,1');
Route::get('auth/native-login', [GoogleAuthController::class , 'handleNativeTokenLogin'])->name('auth.native.token');

// Logout route (authenticated users only)
Route::middleware('auth')->post('logout', [GoogleAuthController::class , 'logout'])->name('logout');

// Public landing page / Home (Redirects directly to Mis Viajes if session is active)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('trips.index', request()->query());
    }
    return view('landing');
})->name('home');


// Dashboard redirect to trips
Route::get('/dashboard', fn() => redirect()->route('trips.index'))->name('dashboard');

// Public info pages
Route::get('/contacto', fn() => view('pages.contact'))->name('contact');
Route::get('/contact', fn() => redirect()->route('contact', [], 301));
Route::post('/contacto', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit')->middleware('throttle:5,1');

Route::get('/terminos-de-uso', fn() => view('pages.terms'))->name('terms');
Route::get('/terms', fn() => redirect()->route('terms', [], 301));
Route::get('/terms-of-service', fn() => redirect()->route('terms', [], 301));

Route::get('/privacidad', fn() => view('pages.privacy'))->name('privacy');
Route::get('/privacy', fn() => redirect()->route('privacy', [], 301));
Route::get('/privacy-policy', fn() => redirect()->route('privacy', [], 301));

Route::get('/reembolsos', fn() => redirect()->to('/terminos-de-uso#cancelacion', 301))->name('refunds');
Route::get('/refunds', fn() => redirect()->to('/terminos-de-uso#cancelacion', 301));
Route::get('/refund-policy', fn() => redirect()->to('/terminos-de-uso#cancelacion', 301));

Route::get('/planes', fn() => redirect()->to('/#planes', 301))->name('pricing');
Route::get('/pricing', fn() => redirect()->to('/#planes', 301));

Route::get('/rgpd', fn() => view('pages.gdpr'))->name('gdpr');
Route::get('/seguridad', fn() => view('pages.security'))->name('security');
Route::get('/.well-known/assetlinks.json', function () {
    $path = public_path('.well-known/assetlinks.json');
    if (file_exists($path)) {
        return response()->file($path, ['Content-Type' => 'application/json']);
    }
    return response()->json([
        [
            'relation' => ['delegate_permission/common.handle_all_urls'],
            'target' => [
                'namespace' => 'android_app',
                'package_name' => 'com.viantryp.app',
                'sha256_cert_fingerprints' => [
                    '07:0A:5A:84:A1:68:76:FD:FA:3E:8C:58:41:BF:4E:DA:6A:BC:09:CA:BD:94:08:E3:E2:3E:28:94:0A:3C:B5:7C',
                    '2D:D3:DC:36:25:23:7E:FB:E9:0D:E3:D2:46:D2:E4:D1:C1:BA:62:F2:10:CC:4C:73:FA:93:F5:AC:30:22:AB:9E',
                    'A8:67:D4:CB:2E:3D:A3:1D:D2:FF:86:2E:3A:09:72:DE:7E:06:10:17:7B:72:5C:F7:70:2F:A2:6F:AA:9D:97:0A',
                    '54:FA:5F:6B:7A:39:28:C2:A3:B1:02:A1:D9:92:5D:28:1E:88:44:0A:44:51:89:17:92:5B:BF:93:B2:4F:A0:ED',
                    '92:A1:99:58:49:71:B6:6B:4A:FC:12:FA:6F:A7:F7:47:9C:7A:3B:50:6C:4A:93:BA:75:DA:7E:4B:41:72:5C:72'
                ]
            ]
        ]
    ]);
});
Route::get('/api/places/photo', [GooglePlacesController::class, 'getPlacePhoto'])->name('places.photo')->middleware('throttle:60,1');
Route::get('/api/image-proxy', [ImageProxyController::class, 'proxy'])->name('image.proxy')->middleware('throttle:60,1');

// Planes redirect from landing page
Route::get('/planes-redirect', function () {
    if (auth()->check()) {
        return redirect()->route('profile.index', ['upgrade' => 'true']);
    }
    return redirect()->route('register', ['intent' => 'upgrade']);
})->name('plans.redirect');

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
    Route::get('api/flights/lookup', [FlightLookupController::class , 'lookup'])->name('api.flights.lookup')->middleware('throttle:20,1');
    Route::get('api/unsplash/search', [TripController::class , 'searchUnsplash'])->name('api.unsplash.search')->middleware('throttle:30,1');

    // Additional trip routes
    Route::post('user/custom-statuses', [TripController::class , 'updateCustomStatuses'])->name('user.custom-statuses');
    Route::post('trips/{trip}/status', [TripController::class , 'updateStatus'])->name('trips.update-status');
    Route::post('trips/{trip}/code', [TripController::class , 'updateCode'])->name('trips.update-code');
    Route::post('trips/{trip}/inline-update', [TripController::class , 'inlineUpdate'])->name('trips.inline-update');
    Route::post('trips/{trip}/client', [TripController::class , 'updateClient'])->name('trips.update-client');
    Route::post('trips/{trip}/cover', [TripController::class , 'uploadCover'])->name('trips.upload-cover')->middleware('throttle:20,1');
    Route::post('trips/{trip}/duplicate', [TripController::class , 'duplicate'])->name('trips.duplicate');
    Route::post('trips/{trip}/generate-share-token', [TripController::class , 'generateShareToken'])->name('trips.generate-share-token');
    Route::get('/trips/{trip}/get-pro-data', [TripController::class, 'getProData'])->name('trips.pro-data');
    Route::post('trips/{trip}/save-pro-state', [TripController::class , 'saveProState'])->name('trips.save-pro-state');
    Route::post('trips/{trip}/ai/chat-agent', [TripAiController::class, 'handleChat'])->name('trips.ai.chat')->middleware('throttle:10,1');
    Route::post('trips/{trip}/upload-attachment', [TripController::class , 'uploadAttachment'])->name('trips.upload-attachment')->middleware('throttle:20,1');
    Route::post('trips/{trip}/send-email', [TripController::class , 'sendEmail'])->name('trips.send-email')->middleware('throttle:10,1');
    Route::post('trips/bulk-delete', [TripController::class , 'bulkDelete'])->name('trips.bulk-delete');
    Route::post('trips/bulk-duplicate', [TripController::class , 'bulkDuplicate'])->name('trips.bulk-duplicate');
    Route::post('trips/{trip}/invite', [TripController::class , 'inviteCollaborator'])->name('trips.invite');
    Route::post('trips/{trip}/transfer', [TripController::class , 'transferOwnership'])->name('trips.transfer');
    Route::get('trips/accept-invite/{token}', [TripController::class , 'acceptInvite'])->name('trips.accept-invite');
    Route::get('trips/{trip}/collaborators', [TripController::class, 'getCollaborators'])->name('trips.collaborators.get');
    Route::post('trips/{trip}/collaborators/remove', [TripController::class, 'removeCollaborator'])->name('trips.collaborators.remove');
    Route::post('trips/{trip}/collaborators/leave', [TripController::class, 'leaveCollaboration'])->name('trips.collaborators.leave');

    // Document routes
    Route::delete('documents/{document}', [\App\Http\Controllers\TripDocumentController::class , 'destroy'])->name('documents.destroy');
    Route::get('documents/{document}/download', [\App\Http\Controllers\TripDocumentController::class , 'download'])->name('documents.download')->withoutMiddleware('auth');
    // Profile routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class , 'index'])->name('profile.index');
    Route::get('/subscription/usage', [\App\Http\Controllers\ProfileController::class, 'getUsage'])->name('subscription.usage');
    Route::post('/profile/personal', [\App\Http\Controllers\ProfileController::class, 'updatePersonal'])->name('profile.update.personal');
    Route::post('/profile/agency', [\App\Http\Controllers\ProfileController::class, 'updateAgency'])->name('profile.update.agency');
    Route::post('/profile/theme', [\App\Http\Controllers\ProfileController::class, 'updateTheme'])->name('profile.update.theme');
    Route::post('/profile/tutorial', [\App\Http\Controllers\ProfileController::class, 'completeTutorial'])->name('profile.complete.tutorial');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'uploadAvatar'])->name('profile.upload.avatar');
    Route::post('/profile/avatar/delete', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.delete.avatar');
    Route::post('/profile/logo', [\App\Http\Controllers\ProfileController::class, 'uploadLogo'])->name('profile.upload.logo');
    Route::post('/profile/plan', [\App\Http\Controllers\ProfileController::class, 'updatePlan'])->name('profile.update.plan');
    Route::post('/profile/plan/paddle-sync', [\App\Http\Controllers\ProfileController::class, 'syncPaddlePlan'])->name('profile.plan.paddle-sync')->middleware('throttle:10,1');
    Route::post('/profile/plan/verify-code', [\App\Http\Controllers\ProfileController::class, 'verifyPlanCode'])->name('profile.plan.verify-code')->middleware('throttle:5,10');
    Route::post('/profile/plan/request', [\App\Http\Controllers\ProfileController::class, 'requestPlanUpgrade'])->name('profile.plan.request')->middleware('throttle:5,10');
    Route::post('/profile/choose-initial-plan', [\App\Http\Controllers\ProfileController::class, 'chooseInitialPlan'])->name('profile.choose-initial-plan');
    Route::post('/profile/change-account-type', [\App\Http\Controllers\ProfileController::class, 'changeAccountType'])->name('profile.change-account-type');
    Route::post('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.update.password')->middleware('throttle:5,1');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroyAccount'])->name('profile.destroy');

    Route::get('/notifications', function() {
        $user = auth()->user();

        if ($user && $user->trial_ends_at !== null) {
            try {
                $hasValidSub = \Illuminate\Support\Facades\Schema::hasTable('subscriptions') && $user->subscriptions()->valid()->exists();
                if (!$hasValidSub) {
                    if ($user->isTrialActive() && $user->getTrialDaysRemaining() <= 3) {
                        $alreadySent = $user->notifications()
                            ->where('type', \App\Notifications\TrialExpiringNotification::class)
                            ->where('created_at', '>=', now()->subDays(15))
                            ->exists();
                        if (!$alreadySent) {
                            $user->notify(new \App\Notifications\TrialExpiringNotification($user, max(1, $user->getTrialDaysRemaining())));
                        }
                    } elseif ($user->trial_ends_at->isPast()) {
                        if ($user->plan !== \App\Models\User::PLAN_BASICO) {
                            $user->update(['plan' => \App\Models\User::PLAN_BASICO]);
                        }
                        $alreadyExpiredSent = $user->notifications()
                            ->where('type', \App\Notifications\TrialExpiredNotification::class)
                            ->exists();
                        if (!$alreadyExpiredSent) {
                            $user->notify(new \App\Notifications\TrialExpiredNotification($user));
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning('Lifecycle notification auto-check warning: ' . $e->getMessage());
            }
        }

        return response()->json([
            'notifications' => $user->notifications()->latest()->take(10)->get(),
            'unread_count' => $user->unreadNotifications()->count()
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

    // Google Places API details route (requires authenticated editor session)
    Route::match(['get', 'post'], 'api/places/details', [GooglePlacesController::class , 'getPlaceDetails'])->name('places.details')->middleware('throttle:30,1');
});

// Shared trip preview route (no authentication required)
Route::get('trips/share/{token}', [TripController::class , 'share'])->name('trips.share');
