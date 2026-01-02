<?php

namespace App\Services\Role;

use App\Models\Role;

class UpdateService
{
    /**
     * Update an existing Role
     *
     * @param Role $role
     * @param array $data
     * @return Role
     */
    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role->fresh();
    }
}
