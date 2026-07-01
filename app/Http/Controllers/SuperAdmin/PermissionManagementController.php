<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\AuditLog;

class PermissionManagementController extends Controller
{
    /**
     * Display all permissions grouped by domain.
     */
    public function index(): View
    {
        $permissions = Permission::with('roles')->orderBy('name')->get();
        $roles       = Role::orderBy('name')->get();

        $permissionGroups = $permissions->groupBy(function ($permission) {
            $name = $permission->name;
            if (str_contains($name, 'student') || str_contains($name, 'lecturer') ||
                str_contains($name, 'course') || str_contains($name, 'krs') ||
                str_contains($name, 'khs') || str_contains($name, 'grade') ||
                str_contains($name, 'internship') || str_contains($name, 'thesis') ||
                str_contains($name, 'graduation')) {
                return 'Akademik';
            }
            if (str_contains($name, 'finance')) return 'Keuangan';
            if (str_contains($name, 'override')) return 'Override';
            if (str_contains($name, 'permission') || str_contains($name, 'system') ||
                str_contains($name, 'settings') || str_contains($name, 'users') ||
                str_contains($name, 'audit') || str_contains($name, 'impersonate')) {
                return 'Sistem';
            }
            return 'Lainnya';
        });

        return view('super-admin.permission-management', compact('permissionGroups', 'roles', 'permissions'));
    }

    /**
     * Assign a permission to a role (AJAX).
     */
    public function assign(Request $request): JsonResponse
    {
        $request->validate([
            'role_id'       => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role       = Role::findById($request->role_id);
        $permission = Permission::findById($request->permission_id);

        if ($role->name === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Super Admin permission tidak dapat diubah.'], 403);
        }

        $role->givePermissionTo($permission);

        AuditLog::log('permission.assigned', $role, [
            'role'       => $role->name,
            'permission' => $permission->name,
        ]);

        return response()->json(['success' => true, 'message' => "Permission '{$permission->name}' ditambahkan ke '{$role->name}'."]);
    }

    /**
     * Revoke a permission from a role (AJAX).
     */
    public function revoke(Request $request): JsonResponse
    {
        $request->validate([
            'role_id'       => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role       = Role::findById($request->role_id);
        $permission = Permission::findById($request->permission_id);

        if ($role->name === 'super_admin') {
            return response()->json(['success' => false, 'message' => 'Super Admin permission tidak dapat diubah.'], 403);
        }

        $role->revokePermissionTo($permission);

        AuditLog::log('permission.revoked', $role, [
            'role'       => $role->name,
            'permission' => $permission->name,
        ]);

        return response()->json(['success' => true, 'message' => "Permission '{$permission->name}' dicabut dari '{$role->name}'."]);
    }
}
