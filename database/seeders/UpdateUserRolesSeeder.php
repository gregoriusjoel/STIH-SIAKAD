<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUserRolesSeeder extends Seeder
{
    /**
     * Update existing users' roles for payment system
     */
    public function run(): void
    {
        // Update keuangan users to finance role
        DB::table('users')
            ->where('email', 'like', '%keuangan%')
            ->orWhere('email', 'like', '%finance%')
            ->orWhere('email', 'like', '%admin%')
            ->update(['role' => 'finance']);

        $this->command->info('✅ Updated finance users roles');

        // Update mahasiswa users to student role
        DB::table('users')
            ->where('email', 'like', '%mahasiswa%')
            ->orWhere('email', 'like', '%student%')
            ->orWhere('role', null)
            ->update(['role' => 'student']);

        $this->command->info('✅ Updated student users roles');

        // Show summary
        $financeCount = User::where('role', 'finance')->count();
        $studentCount = User::where('role', 'student')->count();

        $this->command->info("Finance users: {$financeCount}");
        $this->command->info("Student users: {$studentCount}");
    }
}
