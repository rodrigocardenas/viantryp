<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$user = User::first(); // Let's use the first user
if (!$user) {
    die("No users found in database!\n");
}

echo "Simulating trip creation for user: {$user->email} (ID: {$user->id})\n";

// Log in as user
Auth::login($user);

try {
    $result = DB::transaction(function () use ($user) {
        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Test Trip from CLI ' . time(),
            'is_pro' => true,
            'status' => 'draft',
            'start_date' => now(),
            'end_date' => now(),
            'travelers' => 1,
            'currency' => 'USD',
            'pro_state' => null
        ]);

        echo "Trip model created. Generating code...\n";
        $code = $trip->generateCode();
        echo "Code generated: {$code}. Trip ID: {$trip->id}\n";

        return $trip;
    });
    echo "SUCCESS: Trip created with ID {$result->id}\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
