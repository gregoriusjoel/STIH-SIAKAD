<?php

// Script to fix wrong parent data
// Run: php artisan tinker < scripts/fix_parent_data.php

use App\Models\ParentModel;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Fixing Parent Data ===\n\n";

$parents = ParentModel::with(['mahasiswa.user'])->get();
echo "Found " . $parents->count() . " parent records\n\n";

foreach ($parents as $parent) {
    if (!$parent->mahasiswa) {
        echo "⚠️  Parent ID {$parent->id}: No mahasiswa linked, skipping\n";
        continue;
    }

    $mahasiswa = $parent->mahasiswa;
    $mahasiswaUserId = $mahasiswa->user_id;

    if ($parent->user_id == $mahasiswaUserId) {
        echo "🔧 Parent ID {$parent->id}: Fixing wrong user_id\n";
        echo "   Mahasiswa: {$mahasiswa->user->name} (NIM: {$mahasiswa->nim})\n";
        echo "   Hubungan: {$parent->hubungan}\n";

        $hubungan = $parent->hubungan ?: 'ortu';
        $email = strtolower($hubungan) . '.' . strtolower(str_replace(' ', '', $mahasiswa->nim)) . '@parent.stih.ac.id';

        $existingParentUser = User::where('email', $email)->first();

        if ($existingParentUser && $existingParentUser->role === 'parent') {
            echo "   ✅ Parent user already exists: {$email}\n";
            $parentUser = $existingParentUser;
        } else {
            $parentName = ucfirst($parent->hubungan ?: 'Orang Tua') . ' ' . $mahasiswa->user->name;

            $parentUser = User::create([
                'name' => $parentName,
                'email' => $email,
                'password' => Hash::make('parent123'),
                'role' => 'parent',
            ]);

            echo "   ✅ Created new parent user: {$email}\n";
        }

        $parent->update(['user_id' => $parentUser->id]);
        echo "   ✅ Updated parent record user_id: {$mahasiswaUserId} → {$parentUser->id}\n\n";

    } else {
        $user = User::find($parent->user_id);
        if ($user && $user->role === 'parent') {
            echo "✅ Parent ID {$parent->id}: Already correct (user: {$user->email})\n\n";
        } else {
            echo "⚠️  Parent ID {$parent->id}: user_id points to unknown or non-parent user (ID: {$parent->user_id})\n\n";
        }
    }
}

echo "=== Done ===\n";
