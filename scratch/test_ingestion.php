<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trip = \App\Models\Trip::first();
$service = new \App\Services\TripAiService();

echo "Testing TripAiService with real input...\n";
$input = "Reservé hotel Marriott Madrid del 12 al 15 de Octubre 2026";

$t0 = microtime(true);
$res = $service->processConversation($trip, $input);
$dur = round(microtime(true) - $t0, 2);

echo "Execution time: {$dur}s\n";
echo "Result:\n";
var_dump($res);
