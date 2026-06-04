<?php

namespace App\Console\Commands;

use App\Models\Airport;
use Illuminate\Console\Command;

class GeocodeAirports extends Command
{
    protected $signature = 'airports:geocode';
    protected $description = 'Populate latitude and longitude for airports using OpenFlights dataset';

    public function handle()
    {
        $this->info("Fetching OpenFlights airports dataset...");
        $url = 'https://raw.githubusercontent.com/jpatokal/openflights/master/data/airports.dat';
        
        $content = @file_get_contents($url);
        if (!$content) {
            $this->error("Failed to download OpenFlights airports dataset.");
            return 1;
        }

        $lines = explode("\n", $content);
        $this->info("Processing " . count($lines) . " airports...");

        $updatedCount = 0;
        $missingIataCount = 0;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $data = str_getcsv($line);
            if (count($data) < 8) {
                continue;
            }

            $iata = trim($data[4]);
            $lat = trim($data[6]);
            $lon = trim($data[7]);

            if (empty($iata) || $iata === '\\N' || strlen($iata) !== 3) {
                $missingIataCount++;
                continue;
            }

            // Update all matching airports in local database that have null coordinates
            $affected = Airport::where('iata_code', $iata)
                ->whereNull('latitude')
                ->update([
                    'latitude' => (float) $lat,
                    'longitude' => (float) $lon,
                    'updated_at' => now(),
                ]);

            if ($affected > 0) {
                $updatedCount += $affected;
            }
        }

        $this->info("Geocoding complete!");
        $this->info("Updated coordinates for {$updatedCount} airports.");
        $this->info("Ignored {$missingIataCount} entries without a valid 3-letter IATA code.");

        return 0;
    }
}
