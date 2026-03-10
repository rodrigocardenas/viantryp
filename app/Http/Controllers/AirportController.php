<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    /**
     * API for Select2 search
     */
    public function apiIndex(Request $request)
    {
        $search = $request->input('q');

        $query = Airport::query();

        if ($search) {
            $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('iata_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });

            // Prioritize exact IATA code match if 3 letters are provided
            if (strlen($search) === 3) {
                $query->orderByRaw("CASE WHEN iata_code = ? THEN 1 ELSE 0 END DESC", [strtoupper($search)]);
            }
        }

        // Limit results for performance
        $airports = $query->limit(30)->get();

        $results = $airports->map(function ($airport) {
            $displayText = $airport->name;
            if ($airport->iata_code) {
                $displayText .= " ({$airport->iata_code})";
            }

            return [
            'id' => $displayText, // We use the full text as ID to match current logic
            'text' => $displayText,
            'city' => $airport->city,
            'country' => $airport->country
            ];
        });

        return response()->json($results);
    }
}
