<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$data = DB::table('mahasiswas')->select('id', 'nim', 'user_id')->get();
echo "✅ Mahasiswa data:\n";
foreach ($data as $row) {
    $user = DB::table('users')->where('id', $row->user_id)->first();
    $name = $user->name ?? 'NO USER';
    echo "  • {$row->nim}: {$name} (user_id: {$row->user_id})\n";
}
