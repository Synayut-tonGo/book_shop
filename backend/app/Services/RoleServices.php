<?php

namespace App\Services;

use App\Models\User;

class RoleServices
{
    /**
     * Create a new class instance.
     */
    public function assignRoleToUser(User $user , int $roleId , int $assignedBy):void
    {
        $user->roles()->syncWithoutDetaching(
            [$roleId => ['assigned_by' => $assignedBy] ]
        );
    }
}
