<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

if (!function_exists('userHasPermission')) {
    function userHasPermission($user, $permission)
    {
        // Validate inputs
        if (!$user || !isset($user->role) || !is_string($user->role) || !is_string($permission)) {
            return false;
        }
        
        $role = $user->role; // e.g., 'admin', 'user01'
        
        try {
            // Validate that role is a valid column name (security check)
            $validRoles = ['admin', 'level1', 'level2', 'level3', 'user', 'guest', 'api_only'];
            $normalizedRole = strtolower(str_replace(' ', '_', $role));
            
            if (!in_array($normalizedRole, $validRoles)) {
                Log::warning('Invalid role detected', ['role' => $role, 'normalized' => $normalizedRole]);
                return false;
            }
            
            //dd($role, $permission); // Debugging line to check role and permission
            return (bool) DB::table('role_permissions')
                ->where('permission', $permission)
                ->value($normalizedRole); // Use normalized role for column lookup
        } catch (\Exception $e) {
            // Log error and return false for safety
            Log::error('Permission check database error', [
                'role' => $role,
                'permission' => $permission,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

if (!function_exists('userHasAnyPermission')) {
    function userHasAnyPermission($user, array $permissions)
    {
        $role = $user->role;

        $count = DB::table('role_permissions')
            ->whereIn('permission', $permissions)
            ->where($role, 1)
            ->count();

        return $count > 0;
    }
}

if (!function_exists('userHasAllPermissions')) {
    function userHasAllPermissions($user, array $permissions)
    {
        $role = $user->role;

        $count = DB::table('role_permissions')
            ->whereIn('permission', $permissions)
            ->where($role, 1)
            ->count();

        return $count === count($permissions);
    }
}
