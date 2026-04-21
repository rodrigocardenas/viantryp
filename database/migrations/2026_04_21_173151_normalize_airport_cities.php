<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mappings = [
            'ATH' => 'Atenas',
            'NRT' => 'Tokio',
            'BRU' => 'Bruselas',
            'MXP' => 'Milán',
            'STN' => 'Londres',
            'LTN' => 'Londres',
            'CDG' => 'París',
            'SAW' => 'Estambul',
            'NYO' => 'Estocolmo',
            'VST' => 'Estocolmo',
            'CRL' => 'Bruselas',
            'IAD' => 'Washington',
            'BWI' => 'Washington',
            'WMI' => 'Varsovia',
            'BVA' => 'París',
            'TRF' => 'Oslo',
            'EWR' => 'Nueva York',
            'ICN' => 'Seúl',
            'KNO' => 'Medán',
            'ORY' => 'París',
            'LIN' => 'Milán',
            'HUX' => 'Huatulco',
            'BWA' => 'Bhairahawa',
            'RDO' => 'Varsovia',
            'SEN' => 'Londres',
            'VCE' => 'Venecia',
            'HND' => 'Tokio',
            'KIX' => 'Osaka',
            'ITM' => 'Osaka',
            'NGO' => 'Nagoya',
            'HEL' => 'Helsinki',
            'CPH' => 'Copenhague',
            'GIG' => 'Río de Janeiro',
            'GRU' => 'Sao Paulo',
            'EZE' => 'Buenos Aires',
            'FRA' => 'Frankfurt',
            'MUC' => 'Múnich',
            'VIE' => 'Viena',
            'PRG' => 'Praga',
            'BCN' => 'Barcelona',
            'MAD' => 'Madrid',
            'LIS' => 'Lisboa',
            'AMS' => 'Ámsterdam',
            'DUB' => 'Dublín',
            'ZRH' => 'Zúrich',
            'GVA' => 'Ginebra',
            'OSL' => 'Oslo',
            'ARN' => 'Estocolmo',
            'KEF' => 'Reykjavík',
            'TLS' => 'Toulouse',
            'BGY' => 'Milán',
            'LDE' => 'Lourdes',
            'LYS' => 'Lyon',
            'MRS' => 'Marsella',
            'NCE' => 'Niza',
        ];

        foreach ($mappings as $iata => $city) {
            DB::table('airports')
                ->where('iata_code', $iata)
                ->update(['city' => $city]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // To reverse, we would need the original city names. 
        // Since we don't have them easily without a backup, 
        // we'll leave it empty for now as these are data fixes.
    }
};
