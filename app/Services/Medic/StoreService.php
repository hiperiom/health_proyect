<?php

namespace App\Services\Medic;

use App\Models\Medic;
use App\Helpers\NameHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreService
{
    /**
     * Create a new Medic
     *
     * @param array $data
     * @return Medic
     */
    public function execute(array $data): Medic
    {
        return DB::transaction(function () use ($data) {

            if (request()->hasFile('avatar')) {
                $data['profile_photo_path'] = request()->file('avatar')->store('user_avatars', 'public');
            }

            // Set default password if not provided
            if (!isset($data['password']) || empty($data['password'])) {
                $data['password'] = '12345678';
            }

            $firstName = NameHelper::extractFirstName($data['first_names']);
            $lastName = NameHelper::extractFirstName($data['last_names']);

            $fullName = trim($firstName . ' ' . $lastName);

            $medic =   Medic::create([
                    'name' => $fullName,
                    'dni' => $data['dni'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'profile_photo_path' => $data['profile_photo_path'] ?? NULL,
                ]);

            return $medic;
        });
    }
}
