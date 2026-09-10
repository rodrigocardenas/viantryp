<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$key = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');

$models = [
    'gemini-3.1-flash-lite',
    'gemini-flash-latest'
];

foreach ($models as $m) {
    $startTime = microtime(true);
    try {
        $res = Illuminate\Support\Facades\Http::timeout(40)->withOptions([
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_SSL_VERIFYPEER => false
            ]
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$m}:generateContent?key={$key}", [
            'contents' => [['parts' => [['text' => 'Extrae en JSON cualquier reserva: Vuelo AV123 Bogotá a Madrid 15 de Octubre 14:00']]]],
            'generationConfig' => ['responseMimeType' => 'application/json']
        ]);
        $duration = round(microtime(true) - $startTime, 2);
        echo "Model {$m} ({$duration}s): status " . $res->status() . " -> " . trim(str_replace("\n", " ", substr($res->body(), 0, 200))) . "\n";
    } catch (\Throwable $e) {
        $duration = round(microtime(true) - $startTime, 2);
        echo "Model {$m} ({$duration}s): EXCEPTION: " . $e->getMessage() . "\n";
    }
}
