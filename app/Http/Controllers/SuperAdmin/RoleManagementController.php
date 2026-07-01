<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\AuditLog;

class RoleManagementController extends Controller
{
    /**
     * Display list of all roles with their permissions.
     */
    public function index(): View
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $allPermissions = Permission::orderBy('name')->get();

        // Group permissions by domain for cleaner UI
        $permissionGroups = $allPermissions->groupBy(function ($permission) {
            $name = $permission->name;
            if (str_contains($name, 'student') || str_contains($name, 'lecturer') ||
                str_contains($name, 'course') || str_contains($name, 'krs') ||
                str_contains($name, 'khs') || str_contains($name, 'grade') ||
                str_contains($name, 'internship') || str_contains($name, 'thesis') ||
                str_contains($name, 'graduation')) {
                return 'Akademik';
            }
            if (str_contains($name, 'finance')) {
                return 'Keuangan';
            }
            if (str_contains($name, 'override')) {
                return 'Override';
            }
            if (str_contains($name, 'permission') || str_contains($name, 'system') ||
                str_contains($name, 'settings') || str_contains($name, 'users') ||
                str_contains($name, 'audit') || str_contains($name, 'impersonate')) {
                return 'Sistem';
            }
            return 'Lainnya';
        });

        return view('super-admin.role-management', compact('roles', 'allPermissions', 'permissionGroups'));
    }

    /**
     * Update permissions assigned to a role.
     */
    public function updatePermissions(Request $request, Role $role): RedirectResponse
    {
        // Prevent modifying super_admin role permissions through UI
        if ($role->name === 'super_admin') {
            return back()->with('error', 'Permission Super Admin tidak dapat diubah melalui UI.');
        }

        $validated = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $oldPermissions = $role->permissions->pluck('name')->sort()->values()->toArray();
        $newPermissions = $validated['permissions'] ?? [];

        $role->syncPermissions($newPermissions);

        AuditLog::log(
            action: 'role.permissions_updated',
            auditable: $role,
            meta: ['role' => $role->name],
            before: ['permissions' => $oldPermissions],
            after: ['permissions' => array_values(array_unique($newPermissions))]
        );

        return back()->with('success', "Permission untuk role '{$role->name}' berhasil diperbarui.");
    }
}
