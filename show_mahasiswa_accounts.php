<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n✅ MAHASISWA ACCOUNTS\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Cek mahasiswa dengan user dan email kampus
$mahasiswa = DB::table('mahasiswas as m')
    ->join('users as u', 'm.user_id', '=', 'u.id')
    ->whereNotNull('m.email_kampus')
    ->select(
        'm.nim',
        'm.user_id',
        'm.email_pribadi',
        'm.email_kampus',
        'm.email_aktif',
        'm.is_default_password',
        'u.name',
        'u.email as user_email',
        'u.password'
    )
    ->get();

if ($mahasiswa->count() > 0) {
    echo "📊 Total Mahasiswa with Email Kampus: " . $mahasiswa->count() . "\n\n";
    
    foreach ($mahasiswa as $m) {
        echo "┌─ NIM: {$m->nim}\n";
        echo "│  Nama: {$m->name}\n";
        echo "│  User Email: {$m->user_email}\n";
        echo "│  Email Pribadi: " . ($m->email_pribadi ?? 'NULL') . "\n";
        echo "│  Email Kampus: {$m->email_kampus}\n";
        echo "│  Email Aktif: {$m->email_aktif}\n";
        echo "│\n";
        echo "│  🔐 Login Options:\n";
        if ($m->user_email) {
            echo "│     1. Email: {$m->user_email}\n";
        }
        if ($m->email_pribadi) {
            echo "│     2. Email: {$m->email_pribadi}\n";
        }
        echo "│     3. Email: {$m->email_kampus}\n";
        echo "│\n";
        echo "│  Password: {$m->nim} (NIM-based)\n";
        echo "│  Is Default: " . ($m->is_default_password ? 'YES' : 'NO') . "\n";
        echo "└─\n\n";
    }
} else {
    echo "❌ Tidak ada mahasiswa dengan email kampus\n\n";
}

echo "Total: " . $mahasiswa->count() . " mahasiswa dengan email kampus\n";
