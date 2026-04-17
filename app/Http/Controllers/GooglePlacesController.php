<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GooglePlacesController extends Controller
{
    /**
     * Get detailed information about a place using its place_id.
     */
    public function getPlaceDetails(Request $request)
    {
        $request->validate([
            'place_id' => 'required|string',
        ]);

        $placeId = $request->input('place_id');
        $apiKey = config('services.google.places_api_key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Google Places API key not configured'
            ], 500);
        }

        try {
            $response = Http::get("https://maps.googleapis.com/maps/api/place/details/json", [
                'place_id' => $placeId,
                'fields' => 'name,formatted_address,photos,rating,reviews,opening_hours,website,international_phone_number,price_level,types',
                'key' => $apiKey,
            ]);

            $data = $response->json();

            if ($data['status'] !== 'OK') {
                return response()->json([
                    'error' => 'Failed to fetch place details',
                    'status' => $data['status']
                ], 400);
            }

            // Process photos to include proxy URLs (hiding API key)
            // Limit to 3 photos to reduce API costs
            if (isset($data['result']['photos']) && is_array($data['result']['photos'])) {
                $data['result']['photos'] = array_slice($data['result']['photos'], 0, 3);
                $disk = \Illuminate\Support\Facades\Storage::disk('public');
                
                foreach ($data['result']['photos'] as &$photo) {
                    if (isset($photo['photo_reference'])) {
                        $photoreference = $photo['photo_reference'];
                        $filename = md5($photoreference) . '.jpg';
                        $path = "places/{$filename}";
                        
                        // Download the photo immediately if we don't have it saved
                        if (!$disk->exists($path)) {
                            $imgUrl = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference={$photoreference}&key={$apiKey}";
                            try {
                                $imgResponse = Http::timeout(10)->get($imgUrl);
                                if ($imgResponse->successful()) {
                                    $disk->put($path, $imgResponse->body());
                                }
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error('Google Places Pre-fetch Photo Download Error: ' . $e->getMessage());
                            }
                        }
                        
                        if ($disk->exists($path)) {
                            // If successfully saved, the URL we return to the frontend is the DIRECT local url!
                            // This guarantees the frontend saves the URL of our own server in the DB forever.
                            $photo['url'] = asset("storage/{$path}");
                        } else {
                            // Ultimate fallback
                            $photo['url'] = route('places.photo', ['ref' => $photoreference]);
                        }
                    }
                }
            }

            return response()->json($data['result']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPlacePhoto(Request $request)
    {
        $request->validate([
            'ref' => 'required|string',
        ]);

        $photoreference = $request->input('ref');
        $apiKey = config('services.google.places_api_key');

        if (!$apiKey) {
            return response()->json(['error' => 'API key missing'], 500);
        }

        // Generate a unique filename based on the photo reference
        $filename = md5($photoreference) . '.jpg';
        $disk = \Illuminate\Support\Facades\Storage::disk('public');
        $path = "places/{$filename}";

        // If we already downloaded this photo, serve it from our own local cache
        if ($disk->exists($path)) {
            return redirect(asset("storage/{$path}"));
        }

        $url = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference={$photoreference}&key={$apiKey}";

        try {
            // First time this photo is requested, download it from Google
            $response = Http::get($url);
            
            if ($response->successful()) {
                // Save it to disk so we never pay for it again
                $disk->put($path, $response->body());
                return redirect(asset("storage/{$path}"));
            }
        } catch (\Exception $e) {
            // If download fails, we fall through to the old redirect mechanism
            \Illuminate\Support\Facades\Log::error('Google Places Photo Download Error: ' . $e->getMessage());
        }

        // Fallback: redirect directly to Google (incurs cost, but ensures image loads if cache fails)
        return redirect($url);
    }
}
