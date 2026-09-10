<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trip = \App\Models\Trip::first();
if ($trip) {
    echo "Trip ID: " . $trip->id . "\n";
    echo "Title: " . $trip->title . "\n";
    echo "ai_queries_count: " . ($trip->ai_queries_count ?? 0) . "\n";
    echo "hasReachedAiLimit: " . ($trip->hasReachedAiLimit() ? "YES (Limit reached)" : "NO") . "\n";
} else {
    echo "No trips found.\n";
}

echo "GEMINI_API_KEY env: " . (env('GEMINI_API_KEY') ? 'Configured' : 'NOT CONFIGURED / EMPTY') . "\n";
