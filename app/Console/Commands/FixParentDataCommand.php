<?php

namespace App\Console\Commands;

use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FixParentDataCommand extends Command
{
    protected $signature = 'fix:parent-data';
    protected $description = 'Fix parent records where user_id incorrectly points to mahasiswa user';

    public function handle()
    {
        $this->info('=== Fixing Parent Data ===');
        $this->newLine();

        $parents = ParentModel::with(['mahasiswa.user'])->get();
        $this->info("Found {$parents->count()} parent records");
        $this->newLine();

        $fixed = 0;
        $skipped = 0;
        $correct = 0;

        foreach ($parents as $parent) {
            if (!$parent->mahasiswa) {
                $this->warn("Parent ID {$parent->id}: No mahasiswa linked, skipping");
                $skipped++;
                continue;
            }

            $mahasiswa = $parent->mahasiswa;
            $mahasiswaUserId = $mahasiswa->user_id;

            // Check if user_id in parent table points to the mahasiswa's user (WRONG)
            if ($parent->user_id == $mahasiswaUserId) {
                $this->line("🔧 Parent ID {$parent->id}: Fixing wrong user_id");
                $this->line("   Mahasiswa: {$mahasiswa->user->name} (NIM: {$mahasiswa->nim})");
                $this->line("   Hubungan: {$parent->hubungan}");

                // Generate email for parent
                $hubungan = $parent->hubungan ?: 'ortu';
                $email = strtolower($hubungan) . '.' . strtolower(str_replace(' ', '', $mahasiswa->nim)) . '@parent.stih.ac.id';

                // Check if parent user already exists
                $existingParentUser = User::where('email', $email)->first();

                if ($existingParentUser && $existingParentUser->role === 'parent') {
                    $this->line("   ✅ Parent user already exists: {$email}");
                    $parentUser = $existingParentUser;
                } else {
                    // Generate parent name
                    $parentName = ucfirst($parent->hubungan ?: 'Orang Tua') . ' ' . $mahasiswa->user->name;

                    // Create new parent user
                    $parentUser = User::create([
                        'name' => $parentName,
                        'email' => $email,
                        'password' => Hash::make('parent123'),
                        'role' => 'parent',
                    ]);

                    $this->line("   ✅ Created new parent user: {$email}");
                }

                // Update parent record
                $parent->update(['user_id' => $parentUser->id]);
                $this->line("   ✅ Updated parent record user_id: {$mahasiswaUserId} → {$parentUser->id}");
                $this->newLine();

                $fixed++;
            } else {
                // Check if user_id points to a valid parent user
                $user = User::find($parent->user_id);
                if ($user && $user->role === 'parent') {
                    $this->line("✅ Parent ID {$parent->id}: Already correct (user: {$user->email})");
                    $correct++;
                } else {
                    $this->warn("⚠️  Parent ID {$parent->id}: user_id points to unknown or non-parent user (ID: {$parent->user_id})");
                    $skipped++;
                }
            }
        }

        $this->newLine();
        $this->info('=== Summary ===');
        $this->line("Fixed: {$fixed}");
        $this->line("Already correct: {$correct}");
        $this->line("Skipped: {$skipped}");
        $this->newLine();
        $this->info('Done!');

        return 0;
    }
}
