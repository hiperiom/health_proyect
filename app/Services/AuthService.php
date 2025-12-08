<?php

namespace App\Services;

use App\Models\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Attempt to login a user by email or name and password.
     *
     * @param string $identifier Email or name to identify the user
     * @param string $password
     * @return array{user: Auth, token: string}
     * @throws \Exception When credentials are invalid
     */
    public function login(string $identifier, string $password): array
    {
        $identifier = trim($identifier);

        $auth = new Auth();
        $auth = $auth->where('email', $identifier);
        $auth = $auth->orWhere('dni', $identifier);
        $auth = $auth->first();

        if  (
                ! $auth || 
                ! Hash::check(
                    $password, 
                    $auth->password
                )
            ) {
            throw new \Exception('Credenciales inválidas.');
        }

        $token = $auth->createToken('auth_token')->plainTextToken;

        return [
            'user' => $auth,
            'token' => $token,
        ];
    }
}
