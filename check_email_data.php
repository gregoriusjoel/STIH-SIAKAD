<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$data = DB::table('mahasiswas as m')
    ->join('users as u', 'm.user_id', '=', 'u.id')
    ->select('m.id', 'm.nim', 'u.name', 'm.email', 'm.email_pribadi', 'm.email_kampus', 'm.email_aktif')
    ->get();

echo "✅ Mahasiswa detail:\n";
foreach ($data as $row) {
    echo "\n  NIM: {$row->nim}\n";
    echo "  Name: {$row->name}\n";
    echo "  Old Email: {$row->email}\n";
    echo "  Email Pribadi: {$row->email_pribadi}\n";
    echo "  Email Kampus: {$row->email_kampus}\n";
    echo "  Email Aktif: {$row->email_aktif}\n";
}
