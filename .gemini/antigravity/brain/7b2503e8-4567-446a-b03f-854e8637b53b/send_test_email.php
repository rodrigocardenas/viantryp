<?php

require __DIR__.'/../../../../../vendor/autoload.php';
$app = require_once __DIR__.'/../../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = new \App\Models\User([
    'name' => 'Juan Camacho',
    'email' => 'jcamacho1720@gmail.com'
]);

\Illuminate\Support\Facades\Notification::route('mail', 'jcamacho1720@gmail.com')
    ->notify(new \App\Notifications\WelcomeNotification($user));

echo "Email enviado exitosamente a jcamacho1720@gmail.com\n";
