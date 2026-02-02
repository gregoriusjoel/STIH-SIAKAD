<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (['villages','provinces','cities'] as $t) {
    echo "Table: $t\n";
    try {
        $cols = Illuminate\Support\Facades\Schema::getColumnListing($t);
        print_r($cols);
        $count = Illuminate\Support\Facades\DB::table($t)->count();
        echo "Count: $count\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "---\n";
}
