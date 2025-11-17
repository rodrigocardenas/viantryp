<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::statement('CREATE DATABASE IF NOT EXISTS viantryp_testing');
    echo "Database 'viantryp_testing' created successfully or already exists.\n";
} catch (Exception $e) {
    echo "Error creating database: " . $e->getMessage() . "\n";
}
