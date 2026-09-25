<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cached_flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number', 20)->index();
            $table->date('flight_date')->index();
            $table->string('airline_name')->nullable();
            $table->string('airline_iata', 10)->nullable();
            $table->string('airline_icao', 10)->nullable();
            $table->string('departure_airport_iata', 10)->nullable();
            $table->string('departure_airport_name')->nullable();
            $table->string('departure_city')->nullable();
            $table->string('departure_datetime')->nullable();
            $table->string('departure_terminal', 20)->nullable();
            $table->string('departure_gate', 20)->nullable();
            $table->string('arrival_airport_iata', 10)->nullable();
            $table->string('arrival_airport_name')->nullable();
            $table->string('arrival_city')->nullable();
            $table->string('arrival_datetime')->nullable();
            $table->string('arrival_terminal', 20)->nullable();
            $table->string('flight_status', 50)->nullable();
            $table->string('aircraft_model')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->unique(['flight_number', 'flight_date'], 'flight_number_date_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_flights');
    }
};
