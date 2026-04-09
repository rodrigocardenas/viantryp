<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GooglePlacesController extends Controller
{
    /**
     * Get detailed information about a place using its place_id.
     */
    public function getPlaceDetails(Request $request)
    {
        $request->validate([
            'place_id' => 'required|string',
            'include_photos' => 'nullable|boolean',
        ]);

        $placeId = $request->input('place_id');
        $includePhotos = $request->boolean('include_photos', false);
        $apiKey = config('services.google.places_api_key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Google Places API key not configured'
            ], 500);
        }

        try {
            $fields = 'name,formatted_address,rating,reviews,opening_hours,website,international_phone_number,price_level,types';
            if ($includePhotos) {
                $fields .= ',photos';
            }

            $response = Http::get("https://maps.googleapis.com/maps/api/place/details/json", [
                'place_id' => $placeId,
                'fields' => $fields,
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
            if (isset($data['result']['photos']) && is_array($data['result']['photos'])) {
                foreach ($data['result']['photos'] as &$photo) {
                    if (isset($photo['photo_reference'])) {
                        $photo['url'] = route('places.photo', ['ref' => $photo['photo_reference']]);
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

        $url = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference={$photoreference}&key={$apiKey}";

        // We can use cache here if we want to avoid redundant redirects, 
        // but Google's URL usually redirects to a temporary one anyway.
        return redirect($url);
    }
}
