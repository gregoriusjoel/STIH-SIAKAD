<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$data = DB::table('mahasiswas as m')
    ->join('users as u', 'm.user_id', '=', 'u.id')
    ->select('m.id', 'm.nim', 'u.name', 'm.email_pribadi', 'm.email_kampus', 'm.email_aktif', 'm.is_default_password', 'm.account_automation_at')
    ->get();

echo "\n✅ HASIL OTOMASI BERHASIL!\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

foreach ($data as $row) {
    echo "NIM: {$row->nim}\n";
    echo "  Name: {$row->name}\n";
    echo "  Email Kampus: {$row->email_kampus}\n";
    echo "  Email Pribadi: {$row->email_pribadi}\n";
    echo "  Email Aktif: {$row->email_aktif}\n";
    echo "  Default Password: " . ($row->is_default_password ? '✅ YES' : '❌ NO') . "\n";
    echo "  Automated At: {$row->account_automation_at}\n";
    echo "\n";
}
