<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\UserProfile\StoreService as StoreUserProfileService;
use Illuminate\Support\Arr;

class RegistrationService
{
    private StoreUserProfileService $storeUserProfileService;
    public function __construct(StoreUserProfileService $storeUserProfileService)
    {
        $this->storeUserProfileService = $storeUserProfileService;
    }
    /**
     * Create a new Auth user, hash password and return user + profile + token.
     *
     * @param array $data
     * @return array{user: array, profile: array, token: string}
     */
    public function store(array $data): array
    {
        return DB::transaction(function () use ($data) {
            
            $user = User::create([
                'dni' => $data['dni'] ?? null,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            $data['user_id'] = $user->id;
            
            $paramsProfile = Arr::only($data, [
                'user_id',
                'first_names',
                'last_names',
                'gender',
            ]);
            $profile = $this->storeUserProfileService->store($paramsProfile);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user->toArray(),
                'profile' => $profile->toArray(), 
                'token' => $token
            ];
        });
    }
}
