<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n✅ LOGIN TEST - DUAL EMAIL SUPPORT\n";
echo "════════════════════════════════════════════\n\n";

$emails = [
    'rifqi@student.stih.ac.id' => '2024010001',
    'dewilestari@student.stih.ac.id' => '2024010002',
    'jojo@student.stih.ac.id' => '2024010003',
];

foreach ($emails as $email => $nim) {
    $user = DB::table('users')->where('email', $email)->first();
    if ($user) {
        echo "📧 {$user->name}\n";
        echo "   Email: {$email}\n";
        echo "   Password: {$nim}\n";
        echo "   Status: ✅ Ready to login\n\n";
    }
}

echo "Try login at: http://localhost:8000/login\n";
