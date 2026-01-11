<?php

namespace App\Services\UserProfile;

use App\Models\UserProfile;

class UpdateService
{
    /**
     * Update the specified UserProfile
     *
     * @param UserProfile $modelInstance
     * @param array $data
     * @return UserProfile
     */
    public function execute(UserProfile $modelInstance, array $data): UserProfile
    {
        $modelInstance->update($data);
        return $modelInstance->fresh();
    }
}