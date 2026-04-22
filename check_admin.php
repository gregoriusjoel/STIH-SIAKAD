<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n✅ CHECK ADMIN USERS\n";
echo "════════════════════════════════════════════════════════════════\n\n";

// Cek semua users dengan role admin
$admins = DB::table('users')
    ->where('role', 'admin')
    ->orWhere('role_id', 1)
    ->orWhere('is_admin', true)
    ->get();

if ($admins->count() > 0) {
    echo "📊 Total Admin Users: " . $admins->count() . "\n\n";
    
    foreach ($admins as $admin) {
        echo "┌─ Admin: {$admin->name}\n";
        echo "│  ID: {$admin->id}\n";
        echo "│  Email: {$admin->email}\n";
        echo "│  Password Hash: " . (strlen($admin->password) > 0 ? '✓ SET (' . substr($admin->password, 0, 20) . '...)' : '✗ NOT SET') . "\n";
        
        // Cek email fields jika ada
        if (isset($admin->email_pribadi)) {
            echo "│  Email Pribadi: " . ($admin->email_pribadi ?? 'NULL') . "\n";
        }
        if (isset($admin->email_kampus)) {
            echo "│  Email Kampus: " . ($admin->email_kampus ?? 'NULL') . "\n";
        }
        if (isset($admin->email_aktif)) {
            echo "│  Email Aktif: " . ($admin->email_aktif ?? 'NULL') . "\n";
        }
        
        echo "│  Role: {$admin->role}\n";
        echo "└─ Created: {$admin->created_at}\n\n";
    }
} else {
    echo "❌ Tidak ada admin users!\n\n";
}

// Cek semua users yang bukan mahasiswa
echo "\n📊 ALL USERS (Non-Mahasiswa):\n";
echo "════════════════════════════════════════════════════════════════\n\n";

$allUsers = DB::table('users')
    ->whereNotNull('email')
    ->orderBy('id')
    ->get();

if ($allUsers->count() > 0) {
    echo "Total Users: " . $allUsers->count() . "\n\n";
    foreach ($allUsers as $user) {
        $status = $user->role == 'admin' ? '👑 ADMIN' : '👤 USER';
        echo "  {$status} | {$user->email} | {$user->name}\n";
    }
} else {
    echo "Tidak ada users\n";
}
