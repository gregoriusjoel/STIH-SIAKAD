<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage-users',
            'manage-students',
            'manage-lecturers',
            'manage-courses',
            'manage-krs',
            'manage-khs',
            'manage-grades',
            'manage-internships',
            'manage-thesis',
            'manage-graduation',
            'manage-finance',
            'manage-system',
            'manage-settings',
            'manage-permissions',
            'view-audit-log',
            'impersonate-user',
            'override-academic-data',
            'override-financial-data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $akademikRole   = Role::firstOrCreate(['name' => 'akademik', 'guard_name' => 'web']);
        $keuanganRole   = Role::firstOrCreate(['name' => 'keuangan', 'guard_name' => 'web']);
        $dosenRole      = Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);
        $parentsRole    = Role::firstOrCreate(['name' => 'parents', 'guard_name' => 'web']);
        $mahasiswaRole  = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);

        // Assign all permissions to super_admin
        $superAdminRole->syncPermissions(Permission::all());

        // Assign specific permissions to akademik
        $akademikRole->syncPermissions([
            'manage-students',
            'manage-lecturers',
            'manage-courses',
            'manage-krs',
            'manage-khs',
            'manage-grades',
            'manage-internships',
            'manage-thesis',
            'manage-graduation',
        ]);

        // Assign specific permissions to keuangan
        $keuanganRole->syncPermissions([
            'manage-finance',
        ]);

        // Create a default Super Admin user if not exists
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@stih.ac.id'],
            [
                'name' => 'Super Admin STIH',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
            ]
        );
        $superAdminUser->assignRole($superAdminRole);

        // Migrate existing users' roles
        $users = User::all();
        foreach ($users as $user) {
            if ($user->id === $superAdminUser->id) {
                continue;
            }

            // Map old role to new Spatie role
            $roleName = null;
            switch ($user->role) {
                case 'admin':
                case 'akademik':
                    $roleName = 'akademik';
                    break;
                case 'finance':
                case 'keuangan':
                    $roleName = 'keuangan';
                    break;
                case 'dosen':
                    $roleName = 'dosen';
                    break;
                case 'parent':
                case 'parents':
                    $roleName = 'parents';
                    break;
                case 'student':
                case 'mahasiswa':
                    $roleName = 'mahasiswa';
                    break;
            }

            if ($roleName) {
                $user->assignRole($roleName);
            }
        }
    }
}
