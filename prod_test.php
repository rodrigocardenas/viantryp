<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

$data = [
    'name' => 'Juan Camacho (Viajes GPS)',
    'user_email' => 'jcamacho.viajesgps@gmail.com'
];

try {
    Mail::send('emails.welcome', $data, function ($m) {
        $m->to('jcamacho.viajesgps@gmail.com')->subject('Copia de Bienvenida Viantryp');
    });
    echo "Email enviado exitosamente a jcamacho.viajesgps@gmail.com.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
