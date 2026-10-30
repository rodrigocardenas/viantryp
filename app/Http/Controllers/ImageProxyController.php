<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageProxyController extends Controller
{
    /**
     * Proxy and cache external images (Unsplash, etc.) under the viantryp.com domain
     * to prevent blocking on corporate/private firewalls and ensure universal format compatibility.
     */
    public function proxy(Request $request)
    {
        $rawUrl = $request->query('url');
        if (!$rawUrl || !is_string($rawUrl)) {
            abort(400, 'URL parameter is required.');
        }

        $url = trim($rawUrl);
        if (!filter_var($url, FILTER_VALIDATE_URL) || (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://'))) {
            abort(400, 'Invalid URL format.');
        }

        $parsed = parse_url($url);
        $host = strtolower($parsed['host'] ?? '');

        // SSRF protection: block private/internal addresses and resolve DNS to prevent DNS rebinding / NIP.IO bypass
        if (
            empty($host) ||
            $host === 'localhost' ||
            $host === '127.0.0.1' ||
            $host === '::1' ||
            str_ends_with($host, '.local') ||
            str_ends_with($host, '.test') ||
            str_ends_with($host, '.internal') ||
            (filter_var($host, FILTER_VALIDATE_IP) && filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false)
        ) {
            abort(403, 'Forbidden host.');
        }

        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            $resolvedIps = @gethostbynamel($host);
            if (empty($resolvedIps) || !is_array($resolvedIps)) {
                abort(403, 'Unresolvable host.');
            }
            foreach ($resolvedIps as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                    abort(403, 'Forbidden resolved IP address.');
                }
            }
        }

        // Target width
        $width = (int) $request->query('w', 1000);
        if ($width <= 0 || $width > 1600) {
            $width = 1000;
        }

        // Cache filename by hashing the original URL and target width
        $filename = md5($url . '_w' . $width) . '.jpg';
        $disk = Storage::disk('public');
        $path = "img-cache/{$filename}";

        // If already cached locally, redirect directly to static asset (Cloudflare & web server cache it)
        if ($disk->exists($path)) {
            return redirect(secure_asset("storage/{$path}"), 301, [
                'Cache-Control' => 'public, max-age=31536000, immutable'
            ]);
        }

        // Optimize download request URL if it's Unsplash
        $fetchUrl = $url;
        if (str_contains($host, 'images.unsplash.com')) {
            // Request standard compressed JPEG to ensure 100% universal compatibility across all devices
            $parts = parse_url($url);
            parse_str($parts['query'] ?? '', $queryParams);
            $queryParams['w'] = $width;
            $queryParams['fit'] = 'max';
            $queryParams['q'] = '80';
            $queryParams['fm'] = 'jpg';
            unset($queryParams['auto']); // Prevent Unsplash from serving AVIF which breaks on older mobile devices

            $scheme = $parts['scheme'] ?? 'https';
            $pathPart = $parts['path'] ?? '';
            $fetchUrl = "{$scheme}://{$host}{$pathPart}?" . http_build_query($queryParams);
        }

        try {
            $response = Http::timeout(8)
                ->withOptions([
                    'allow_redirects' => false,
                ])
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; ViantrypImageProxy/1.0; +https://viantryp.com)',
                    'Accept' => 'image/jpeg,image/webp,image/*,*/*;q=0.8'
                ])
                ->get($fetchUrl);

            $contentType = strtolower($response->header('Content-Type') ?? '');
            $body = $response->body();

            if (
                $response->successful() &&
                str_starts_with($contentType, 'image/') &&
                strlen($body) > 500 &&
                strlen($body) <= 8 * 1024 * 1024
            ) {
                $disk->put($path, $body);

                return redirect(secure_asset("storage/{$path}"), 301, [
                    'Cache-Control' => 'public, max-age=31536000, immutable'
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('ImageProxy Download Warning: ' . $e->getMessage() . ' for ' . $url);
        }

        // Fallback: If download failed, redirect to the original URL
        return redirect($url, 302);
    }
}
