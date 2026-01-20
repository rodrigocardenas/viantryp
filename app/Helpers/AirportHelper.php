<?php

if (!function_exists('getCountryFromCity')) {
    /**
     * Obtiene el país correspondiente a una ciudad desde los datos de aeropuertos
     *
     * @param string $city Nombre de la ciudad
     * @return string País correspondiente o cadena vacía si no se encuentra
     */
    function getCountryFromCity(string $city): string
    {
        static $airportData = null;

        // Cargar datos si no están en caché
        if ($airportData === null) {
            // Ruta relativa desde la ubicación del helper
            $selectorsPath = __DIR__ . '/../../resources/js/data/selectors.json';

            if (file_exists($selectorsPath)) {
                $selectorsData = json_decode(file_get_contents($selectorsPath), true);
                $airportData = $selectorsData['airports'] ?? [];
            } else {
                $airportData = [];
            }
        }

        // Buscar el país por ciudad
        foreach ($airportData as $airport) {
            if (isset($airport['city']) && strtolower($airport['city']) === strtolower($city)) {
                return $airport['country'] ?? '';
            }
        }

        return '';
    }
}
