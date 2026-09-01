<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trip = \App\Models\Trip::first();
config(['services.gemini.model' => 'gemini-3.1-flash-lite']);

$service = new \App\Services\TripAiService();
$t0 = microtime(true);
$res = $service->processConversation($trip, '¿Cuántos días tiene este itinerario y qué recomendaciones me das?');
$dur = round(microtime(true) - $t0, 2);

echo "Execution time: {$dur}s\n";
echo json_encode($res, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
