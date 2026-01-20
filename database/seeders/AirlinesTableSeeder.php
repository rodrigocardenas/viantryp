<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Airline;
use Illuminate\Support\Facades\File;

class AirlinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load airlines data from selectors.json
        $selectorsPath = resource_path('js/data/selectors.json');
        $selectorsData = json_decode(File::get($selectorsPath), true);

        if (isset($selectorsData['airlines'])) {
            foreach ($selectorsData['airlines'] as $airlineData) {
                Airline::updateOrCreate(
                    ['name' => $airlineData['id']], // Use name as unique identifier
                    [
                        'name' => $airlineData['id'],
                        'country' => $airlineData['country'],
                        'carrier_code' => null, // Set to null or derive if possible
                        'logo_path' => null
                    ]
                );
            }
        }
    }
}
