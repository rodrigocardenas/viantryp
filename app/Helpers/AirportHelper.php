<?php

use App\Models\Airport;

if (!function_exists('getCountryFromCity')) {
    /**
     * Obtiene el país correspondiente a una ciudad desde los datos de aeropuertos
     *
     * @param string $city Nombre de la ciudad
     * @return string País correspondiente o cadena vacía si no se encuentra
     */
    function getCountryFromCity(string $city): string
    {
        if (empty($city)) {
            return '';
        }

        // Buscar el país por ciudad en la base de datos
        $airport = Airport::where('city', $city)->first();

        if ($airport) {
            return $airport->country ?? '';
        }

        return '';
    }
}
