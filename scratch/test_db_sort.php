<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
print_r(App\Models\Trip::select(['id', 'user_id', 'title'])->orderBy('created_at', 'desc')->take(3)->get()->toArray());
