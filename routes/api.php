<?php

use App\Http\Controllers\TripAiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'throttle:10,1'])->group(function () {
    // Route::post('/trips/{trip}/ai/chat-agent', [TripAiController::class, 'handleChat'])->name('api.trips.ai.chat');
});
