<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trip = App\Models\Trip::first();
$service = new App\Services\TripAiService();
$res = $service->processConversation($trip, 'Hotel Marriott Bogotá para el Día 1');
print_r($res);
