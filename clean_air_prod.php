<?php 
require '/var/www/html/viantryp/vendor/autoload.php';
$app = require_once '/var/www/html/viantryp/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$count = DB::table('airlines')->whereNull('country')->orWhere('country', '')->delete();
echo "Deleted $count records." . PHP_EOL;
$remaining = DB::table('airlines')->count();
echo "Remaining records: $remaining" . PHP_EOL;
