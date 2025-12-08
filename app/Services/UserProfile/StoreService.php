<?php

namespace App\Services\UserProfile;

use App\Models\UserProfile;
class StoreService
{
    /**
     * Create a new Auth user, hash password and return user + token.
     *
     * @param array $data
     * @return UserProfile
     */
    public function store(array $data): UserProfile    
    {
        return UserProfile::create($data);
    }
}
