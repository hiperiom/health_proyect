<?php

namespace App\Services\UserProfile;

use App\Models\UserProfile;
class StoreService
{

    public function execute(int $userId, array $data): UserProfile    
    {
        return UserProfile::create([
            'user_id' => $userId, 
            'first_names' => $data['first_names'],
            'last_names' => $data['last_names'],
            'gender' => $data['gender'],
            'birthday' => date('Y-m-d', strtotime($data['birthday'])),
        ]);
    }
}

