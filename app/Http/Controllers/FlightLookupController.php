<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\CachedFlight;

class FlightLookupController extends Controller
{
    /**
     * Look up a flight by flight number and date.
     * Uses 4 layers:
     * 1. Plan authorization check (Viajero Pro & Negocios)
     * 2. Local permanent database storage (CachedFlight)
     * 3. Monthly safety circuit-breaker (max 380 calls/month)
     * 4. RapidAPI AeroDataBox integration
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'flight_number' => 'required|string|max:20',
            'date' => 'nullable|date',
        ]);

        $user = auth()->user() ?: $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'error_code' => 'UNAUTHENTICATED',
                'message' => 'Debes iniciar sesión para consultar vuelos.',
            ], 401);
        }

        // 1. Check Plan Authorization
        $userPlan = strtolower($user->plan ?? 'básico');
        $isPro = in_array($userPlan, ['avanzado', 'viajero pro', 'colaborativo', 'corporativo', 'negocios'])
            || ($user->trial_ends_at && $user->trial_ends_at->isFuture());

        if (!$isPro) {
            return response()->json([
                'success' => false,
                'error_code' => 'UPGRADE_REQUIRED',
                'message' => 'La búsqueda automática de vuelos está disponible para los planes Viajero Pro y Negocios.',
            ], 403);
        }

        // Clean and normalize input
        $flightNumber = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $request->input('flight_number')));
        $flightDate = $request->input('date') ? date('Y-m-d', strtotime($request->input('date'))) : date('Y-m-d');

        if (empty($flightNumber)) {
            return response()->json([
                'success' => false,
                'message' => 'Ingresa un número de vuelo válido.',
            ], 422);
        }

        // 2. Check Permanent Local Database
        $cached = CachedFlight::where('flight_number', $flightNumber)
            ->where('flight_date', $flightDate)
            ->first();

        if ($cached) {
            return response()->json([
                'success' => true,
                'source' => 'local_database',
                'data' => $this->formatFlightResponse($cached),
            ]);
        }

        // 3. Check Monthly Circuit Breaker
        $monthKey = 'aerodatabox_monthly_count_' . date('Y_m');
        $currentMonthlyUsage = (int) Cache::get($monthKey, 0);
        $monthlyLimit = (int) config('services.aerodatabox.monthly_limit', 380);

        if ($currentMonthlyUsage >= $monthlyLimit) {
            Log::warning("AeroDataBox Monthly Limit ({$monthlyLimit}) reached for " . date('Y-m'));
            return response()->json([
                'success' => false,
                'error_code' => 'MONTHLY_LIMIT_REACHED',
                'message' => 'Se ha alcanzado el límite mensual seguro de consultas a la API de vuelos. Por favor ingresa los datos de tu vuelo manualmente.',
            ], 429);
        }

        // 4. Query RapidAPI AeroDataBox
        $apiKey = config('services.aerodatabox.api_key');
        $apiHost = config('services.aerodatabox.host', 'aerodatabox.p.rapidapi.com');

        if (empty($apiKey)) {
            Log::error('AeroDataBox RapidAPI key is not configured in services.php / .env');
            return response()->json([
                'success' => false,
                'error_code' => 'API_NOT_CONFIGURED',
                'message' => 'El servicio de consulta de vuelos no está configurado actualmente.',
            ], 500);
        }

        try {
            $url = "https://{$apiHost}/flights/number/{$flightNumber}/{$flightDate}";

            $response = Http::withHeaders([
                'x-rapidapi-host' => $apiHost,
                'x-rapidapi-key' => $apiKey,
            ])->timeout(12)->get($url);

            if ($response->status() === 404 || empty($response->json())) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'FLIGHT_NOT_FOUND',
                    'message' => 'No se encontró información para este vuelo en la fecha seleccionada.',
                ], 404);
            }

            if (!$response->successful()) {
                Log::error('AeroDataBox API HTTP error: ' . $response->status() . ' body: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'error_code' => 'API_ERROR',
                    'message' => 'No se pudo obtener información del vuelo en este momento.',
                ], 502);
            }

            $body = $response->json();
            $flightItem = is_array($body) && isset($body[0]) ? $body[0] : (is_array($body) ? $body : null);

            if (!$flightItem || !is_array($flightItem)) {
                return response()->json([
                    'success' => false,
                    'error_code' => 'FLIGHT_NOT_FOUND',
                    'message' => 'No se encontró información para este vuelo.',
                ], 404);
            }

            // Extract airline
            $airlineName = $flightItem['airline']['name'] ?? $flightItem['airline']['iata'] ?? $flightItem['airline']['icao'] ?? null;
            $airlineIata = $flightItem['airline']['iata'] ?? null;
            $airlineIcao = $flightItem['airline']['icao'] ?? null;

            // Extract Departure
            $dep = $flightItem['departure'] ?? [];
            $depIata = $dep['airport']['iata'] ?? null;
            $depName = $dep['airport']['name'] ?? $dep['airport']['shortName'] ?? null;
            $depCity = $dep['airport']['municipalityName'] ?? null;
            $depRawTime = $dep['scheduledTime']['local'] ?? $dep['scheduledTimeLocal'] ?? $dep['revisedTime']['local'] ?? null;
            $depTime = $this->formatDateTimeString($depRawTime, $flightDate);
            $depTerminal = $dep['terminal'] ?? null;
            $depGate = $dep['gate'] ?? null;

            // Extract Arrival
            $arr = $flightItem['arrival'] ?? [];
            $arrIata = $arr['airport']['iata'] ?? null;
            $arrName = $arr['airport']['name'] ?? $arr['airport']['shortName'] ?? null;
            $arrCity = $arr['airport']['municipalityName'] ?? null;
            $arrRawTime = $arr['scheduledTime']['local'] ?? $arr['scheduledTimeLocal'] ?? $arr['revisedTime']['local'] ?? null;
            $arrTime = $this->formatDateTimeString($arrRawTime, $flightDate);
            $arrTerminal = $arr['terminal'] ?? null;

            // Fallback for departure from local airports DB if needed
            if (!empty($depIata)) {
                $localDepAirport = \App\Models\Airport::where('iata_code', $depIata)->first();
                if ($localDepAirport) {
                    if (empty($depName)) $depName = $localDepAirport->name;
                    if (empty($depCity)) $depCity = $localDepAirport->city;
                }
            }
            if (empty($depCity)) $depCity = $depName;

            // Fallback for arrival from local airports DB if needed
            if (!empty($arrIata)) {
                $localArrAirport = \App\Models\Airport::where('iata_code', $arrIata)->first();
                if ($localArrAirport) {
                    if (empty($arrName)) $arrName = $localArrAirport->name;
                    if (empty($arrCity)) $arrCity = $localArrAirport->city;
                }
            }
            if (empty($arrCity)) $arrCity = $arrName;

            $status = $flightItem['status'] ?? 'Scheduled';
            $aircraft = $flightItem['aircraft']['model'] ?? null;

            // Increment monthly count upon successful new API call
            Cache::increment($monthKey);

            // Save permanently into local database
            $cachedFlight = CachedFlight::updateOrCreate(
                [
                    'flight_number' => $flightNumber,
                    'flight_date' => $flightDate,
                ],
                [
                    'airline_name' => $airlineName,
                    'airline_iata' => $airlineIata,
                    'airline_icao' => $airlineIcao,
                    'departure_airport_iata' => $depIata,
                    'departure_airport_name' => $depName,
                    'departure_city' => $depCity,
                    'departure_datetime' => $depTime,
                    'departure_terminal' => $depTerminal,
                    'departure_gate' => $depGate,
                    'arrival_airport_iata' => $arrIata,
                    'arrival_airport_name' => $arrName,
                    'arrival_city' => $arrCity,
                    'arrival_datetime' => $arrTime,
                    'arrival_terminal' => $arrTerminal,
                    'flight_status' => $status,
                    'aircraft_model' => $aircraft,
                    'raw_payload' => $flightItem,
                ]
            );

            return response()->json([
                'success' => true,
                'source' => 'api_aerodatabox',
                'data' => $this->formatFlightResponse($cachedFlight),
            ]);

        } catch (\Exception $e) {
            Log::error('AeroDataBox Exception: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error_code' => 'EXCEPTION',
                'message' => 'Ocurrió un error al consultar el vuelo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format CachedFlight model into frontend data structure
     */
    private function formatFlightResponse(CachedFlight $cached): array
    {
        $depLabel = '';
        if ($cached->departure_city && $cached->departure_airport_iata) {
            $depLabel = "{$cached->departure_city} ({$cached->departure_airport_iata})" . ($cached->departure_airport_name ? " - {$cached->departure_airport_name}" : "");
        } elseif ($cached->departure_airport_iata) {
            $depLabel = $cached->departure_airport_iata . ($cached->departure_airport_name ? " - {$cached->departure_airport_name}" : "");
        } else {
            $depLabel = $cached->departure_city ?: $cached->departure_airport_name ?: '';
        }

        $arrLabel = '';
        if ($cached->arrival_city && $cached->arrival_airport_iata) {
            $arrLabel = "{$cached->arrival_city} ({$cached->arrival_airport_iata})" . ($cached->arrival_airport_name ? " - {$cached->arrival_airport_name}" : "");
        } elseif ($cached->arrival_airport_iata) {
            $arrLabel = $cached->arrival_airport_iata . ($cached->arrival_airport_name ? " - {$cached->arrival_airport_name}" : "");
        } else {
            $arrLabel = $cached->arrival_city ?: $cached->arrival_airport_name ?: '';
        }

        return [
            'aerolinea' => $cached->airline_name ?: '',
            'vuelo' => $cached->flight_number,
            'fecha' => $cached->flight_date ? $cached->flight_date->format('Y-m-d') : '',
            'origen' => $depLabel,
            'destino' => $arrLabel,
            'origen_city' => $cached->departure_city ?: '',
            'origen_iata' => $cached->departure_airport_iata ?: '',
            'origen_airport_name' => $cached->departure_airport_name ?: '',
            'destino_city' => $cached->arrival_city ?: '',
            'destino_iata' => $cached->arrival_airport_iata ?: '',
            'destino_airport_name' => $cached->arrival_airport_name ?: '',
            'departure_airport' => $cached->departure_airport_iata ?: '',
            'departure_airport_name' => $cached->departure_airport_name ?: '',
            'departure_city' => $cached->departure_city ?: '',
            'arrival_airport' => $cached->arrival_airport_iata ?: '',
            'arrival_airport_name' => $cached->arrival_airport_name ?: '',
            'arrival_city' => $cached->arrival_city ?: '',
            'salida' => $cached->departure_datetime ?: '',
            'llegada' => $cached->arrival_datetime ?: '',
            'terminal_salida' => $cached->departure_terminal ?: '',
            'puerta_salida' => $cached->departure_gate ?: '',
            'terminal_llegada' => $cached->arrival_terminal ?: '',
            'status' => $cached->flight_status ?: 'Scheduled',
            'aircraft' => $cached->aircraft_model ?: '',
        ];
    }

    /**
     * Format raw time string into YYYY-MM-DDTHH:MM standard datetime-local format
     */
    private function formatDateTimeString(?string $rawTime, string $fallbackDate): ?string
    {
        if (empty($rawTime)) return null;

        // Replace space with T if format is 'YYYY-MM-DD HH:MM'
        $cleaned = str_replace(' ', 'T', trim($rawTime));

        // If it includes seconds or timezone like '2026-09-25T09:00:00+00:00', take first 16 chars
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/', $cleaned)) {
            return substr($cleaned, 0, 16);
        }

        // If it's just 'HH:MM'
        if (preg_match('/^\d{2}:\d{2}/', $cleaned)) {
            return $fallbackDate . 'T' . substr($cleaned, 0, 5);
        }

        return $cleaned;
    }
}
