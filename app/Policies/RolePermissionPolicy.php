<?php

namespace App\Policies;

use App\Models\User;

class RolePermissionPolicy
{
    // Always allow user with ID 1 (super admin) to do anything
    public function before(User $user, $ability)
    {
        if ($user->id === 1) {
            return true;
        }
        // Otherwise, return null to fall back to default policy methods
        return false;
    }
}
