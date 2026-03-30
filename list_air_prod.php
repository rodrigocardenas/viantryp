<?php 
require '/var/www/html/viantryp/vendor/autoload.php';
$app = require_once '/var/www/html/viantryp/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$list = DB::table('airlines')->select('id','name','country','carrier_code')->orderBy('name')->get();
foreach($list as $a) {
    echo "[" . $a->id . "] " . $a->name . " (" . ($a->country ?? 'NULL') . ") [" . ($a->carrier_code ?? 'NULL') . "]" . PHP_EOL;
}
