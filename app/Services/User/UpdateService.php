<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserProfile;
use App\Helpers\NameHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateService
{

    public function execute(User $user, array $data): User
    {
       
        if (request()->hasFile('avatar')) {
            $data['profile_photo_path'] = request()->file('avatar')->store('user_avatars', 'public');
        }

        $firstName = NameHelper::extractFirstName($data['first_names']);
        $lastName = NameHelper::extractFirstName($data['last_names']);

        $fullName = trim($firstName . ' ' . $lastName);

        $user->update([
            'name' => $fullName,
            'dni' => $data['dni'],
            'email' => $data['email'],
            'profile_photo_path' => $data['profile_photo_path'] ?? $user->profile_photo_path,
        ]);
            
        $user->profile()->update([
            'first_names' => $data['first_names'],
            'last_names' => $data['last_names'],
            'gender' => $data['gender'],
            'birthday' => date('Y-m-d', strtotime($data['birthday'])),

        ]);

        return $user->fresh();
    }
}
