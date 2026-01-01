<?php

namespace App\Services\Role;

use App\Models\Role;
class StoreService
{
    /**
     * Create a new Role
     *
     * @param array $data
     * @return Role
     */
    public function store(array $data): Role    
    {
        return Role::create($data);
    }
}
