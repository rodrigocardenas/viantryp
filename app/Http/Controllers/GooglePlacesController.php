<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GooglePlacesController extends Controller
{
    /**
     * Get detailed information about a place using its place_id
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

            return response()->json($data['result']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
