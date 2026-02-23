<?php

namespace App\Console\Commands;

use App\Models\Airport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportAirports extends Command
{
    protected $signature = 'airports:import {file=storage/app/imports/airports.csv}';
    protected $description = 'Import airports from a CSV file with encoding fix';

    public function handle()
    {
        $filePath = base_path($this->argument('file'));

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Importing airports from {$filePath}...");

        // Truncate table to ensure clean sync
        Airport::truncate();

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Could not open file.");
            return 1;
        }

        $count = 0;
        $batch = [];
        $batchSize = 100;

        try {
            while (($line = fgets($handle)) !== FALSE) {
                if (empty(trim($line)))
                    continue;
                if (strpos($line, ';') === false)
                    continue;

                // Convert from potential ISO-8859-1/Windows-1252 to UTF-8
                // We use mb_convert_encoding with 'auto' or explicit sources if needed
                $encodedLine = mb_convert_encoding($line, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');

                $data = str_getcsv($encodedLine, ';');

                // New structure detected: type;name;country;city;iata_code
                if (count($data) < 5) {
                    $this->warn("Skipping line " . ($count + 1) . ": insufficient columns (" . count($data) . ")");
                    continue;
                }

                // Skip header line if it contains the word "iata_code" or "type"
                if (strtolower($data[4]) === 'iata_code' || strtolower($data[0]) === 'type') {
                    $this->info("Skipping header line.");
                    continue;
                }

                $batch[] = [
                    'name' => trim($data[1]),
                    'iata_code' => trim($data[4]),
                    'city' => trim($data[3]),
                    'country' => trim($data[2]),
                    'latitude' => null,
                    'longitude' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    try {
                        Airport::insert($batch);
                        $count += count($batch);
                        $this->info("Imported {$count} airports...");
                        $batch = [];
                    }
                    catch (\Exception $e) {
                        $this->error("Error inserting batch: " . $e->getMessage());
                        // Try inserting one by one to find the culprit
                        foreach ($batch as $row) {
                            try {
                                Airport::create($row);
                                $count++;
                            }
                            catch (\Exception $inner) {
                                $this->error("Error inserting row: " . json_encode($row) . " - " . $inner->getMessage());
                            }
                        }
                        $batch = [];
                    }
                }
            }

            if (!empty($batch)) {
                Airport::insert($batch);
                $count += count($batch);
            }

            $this->info("Successfully imported {$count} airports.");
        }
        catch (\Exception $e) {
            $this->error("General Error: " . $e->getMessage());
            return 1;
        }

        fclose($handle);
        return 0;
    }
}
