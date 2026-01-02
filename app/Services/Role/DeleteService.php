<?php

namespace App\Services\Role;

use App\Models\Role;

class DeleteService
{
    /**
     * Delete an existing Role
     *
     * @param Role $role
     * @return bool
     */
    public function delete(Role $role): bool
    {
        return $role->delete();
    }
}
