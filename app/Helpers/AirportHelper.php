<?php

use App\Models\Airport;

if (!function_exists('getCountryFromCity')) {
    /**
     * Obtiene el país correspondiente a una ciudad desde los datos de aeropuertos
     *
     * @param string $city Nombre de la ciudad
     * @param string|null $iataCode Código IATA opcional para mayor precisión
     * @return string País correspondiente o cadena vacía si no se encuentra
     */
    function getCountryFromCity(string $city, ?string $iataCode = null): string
    {
        if (empty($city) && empty($iataCode)) {
            return '';
        }

        $airport = null;

        // 1. Intentar extraer código IATA exacto si viene entre paréntesis Ej: "Lima (LIM)"
        if (!empty($iataCode)) {
            // Extraer el texto entre paréntesis
            if (preg_match('/\(([A-Z0-9]{3})\)/i', $iataCode, $matches)) {
                $cleanIata = strtoupper($matches[1]);
                $airport = Airport::where('iata_code', $cleanIata)->first();
            }
            else {
                // Si solo mandaron un código limpio de 3 letras (por si acaso)
                $airport = Airport::where('iata_code', $iataCode)->first();
            }
        }

        // 2. Si no hay IATA o no se encontró, buscar por ciudad
        if (!$airport && !empty($city)) {
            // Priorizamos los aeropuertos que sí tengan un código IATA de 3 letras (suelen ser los principales)
            $airport = Airport::where('city', $city)
                ->orderByRaw('LENGTH(iata_code) = 3 DESC')
                ->first();
        }

        if ($airport) {
            return $airport->country ?? '';
        }

        return '';

    }
}

if (!function_exists('getAirportDataByIata')) {
    /**
     * Obtiene los datos del aeropuerto (nombre, ciudad, país) basado en su código IATA.
     * Si se pasa una cadena con paréntesis ej. "Lima (LIM)", extrae el código.
     *
     * @param string|null $iataCodeString
     * @return \App\Models\Airport|null
     */
    function getAirportDataByIata(?string $iataCodeString)
    {
        if (empty($iataCodeString)) {
            return null;
        }

        if (preg_match('/\(([A-Z0-9]{3})\)/i', $iataCodeString, $matches)) {
            $cleanIata = strtoupper($matches[1]);
            return Airport::where('iata_code', $cleanIata)->first();
        }

        return Airport::where('iata_code', $iataCodeString)->first();
    }
}
