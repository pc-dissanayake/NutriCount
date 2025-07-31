<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('userHasPermission')) {
    function userHasPermission($user, $permission)
    {
        $role = $user->role; // e.g., 'admin', 'user01'
        //dd($role, $permission); // Debugging line to check role and permission
        return (bool) DB::table('role_permissions')
            ->where('permission', $permission)
            ->value($role); // dynamic column lookup
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
