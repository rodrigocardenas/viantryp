<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CachedFlight extends Model
{
    protected $table = 'cached_flights';

    protected $fillable = [
        'flight_number',
        'flight_date',
        'airline_name',
        'airline_iata',
        'airline_icao',
        'departure_airport_iata',
        'departure_airport_name',
        'departure_city',
        'departure_datetime',
        'departure_terminal',
        'departure_gate',
        'arrival_airport_iata',
        'arrival_airport_name',
        'arrival_city',
        'arrival_datetime',
        'arrival_terminal',
        'flight_status',
        'aircraft_model',
        'raw_payload',
    ];

    protected $casts = [
        'flight_date' => 'date',
        'raw_payload' => 'array',
    ];
}
