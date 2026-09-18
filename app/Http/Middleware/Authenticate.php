<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $ua = $request->header('User-Agent', '');
        $requestedWith = $request->header('X-Requested-With', '');
        $isApp = $request->has('app') || 
                 $request->query('mode') === 'app' || 
                 $request->cookie('viantryp_app_mode') === '1' ||
                 str_contains($requestedWith, 'viantryp') || 
                 str_contains($requestedWith, 'twa') ||
                 (str_contains($ua, 'Android') && (str_contains($ua, '; wv') || str_contains($ua, 'Version/4.0') || str_contains($ua, 'Viantryp')));

        if ($isApp) {
            return route('app.onboarding');
        }

        return route('home');
    }
}
