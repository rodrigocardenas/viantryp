<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use App\Models\User;

class GoogleSheetService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Viantryp Sync');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        
        $jsonPath = config('google.service_account_json');
        
        if ($jsonPath && file_exists($jsonPath)) {
            $this->client->setAuthConfig($jsonPath);
        }
        
        $this->service = new Sheets($this->client);
    }

    public function appendUser(User $user)
    {
        $spreadsheetId = config('google.sheet_id');
        if (!$spreadsheetId) return;

        $range = 'Hoja 1!A1'; // Empezar desde A1 y dejar que Google busque el final
        
        $values = [
            [
                $user->id,
                $user->name,
                $user->email,
                ucfirst($user->plan ?? 'básico'),
                $user->created_at->format('Y-m-d'),
                $user->country ?? 'No especificado',
                $user->google_id ? 'Google' : 'Directo',
            ],
        ];

        $body = new Sheets\ValueRange([
            'values' => $values
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        try {
            $this->service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        } catch (\Exception $e) {
            \Log::error('Error syncing user to Google Sheets: ' . $e->getMessage());
        }
    }
}
