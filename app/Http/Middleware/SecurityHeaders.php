<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ─── Referrer Policy ──────────────────────────────────────────────────
        // Only sends the domain (not the full path) to external sites.
        // Safe: does not affect any functionality.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ─── Permissions Policy ───────────────────────────────────────────────
        // Disables browser APIs that Viantryp does not use.
        // geolocation=(self) is kept in case a future feature needs it.
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), usb=(), geolocation=(self), payment=*'
        );

        // ─── Content Security Policy ──────────────────────────────────────────
        // Whitelists every external domain actually used by Viantryp:
        //   Scripts : Google Maps, jQuery, jsDelivr (Select2 + Driver.js), Google Auth, Paddle
        //   Styles  : Google Fonts, FontAwesome (cdnjs), jsDelivr
        //   Fonts   : Google Fonts gstatic, FontAwesome (cdnjs)
        //   Images  : any HTTPS source (needed for user-supplied hotel/activity images
        //             from booking sites, Unsplash, Giphy thumbnails, Google Places, etc.)
        //   Connect : Google Maps, Unsplash API, Giphy API, Paddle API
        //   Frames  : Google OAuth popup, Paddle Checkout
        //   Media   : Giphy GIFs (served from *.giphy.com CDN)
        //
        // unsafe-inline is required because Viantryp uses inline <script> and <style>
        // blocks extensively in Blade templates. This does NOT weaken origin whitelisting.
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://maps.googleapis.com https://code.jquery.com https://cdn.jsdelivr.net https://accounts.google.com https://cdn.paddle.com https://buy.paddle.com https://*.paddle.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' https://maps.googleapis.com https://api.unsplash.com https://api.giphy.com https://nominatim.openstreetmap.org https://*.paddle.com https://sandbox-api.paddle.com https://buy.paddle.com https://api.paddle.com",
            "frame-src 'self' https://accounts.google.com https://*.paddle.com https://buy.paddle.com https://sandbox-buy.paddle.com",
            "child-src 'self' https://*.paddle.com https://buy.paddle.com https://sandbox-buy.paddle.com",
            "media-src 'self' https://*.giphy.com",
            "object-src 'none'",
            "base-uri 'self'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
