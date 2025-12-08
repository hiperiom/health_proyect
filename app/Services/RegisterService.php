<?php

namespace App\Services;

use App\Models\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    /**
     * Create a new Auth user, hash password and return user + token.
     *
     * @param array $data
     * @return array{user: Auth, token: string}
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $auth = Auth::create([
                'dni' => $data['dni'] ?? null,
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $auth->createToken('auth_token')->plainTextToken;

            return ['user' => $auth, 'token' => $token];
        });
    }
}
