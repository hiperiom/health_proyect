<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    /**
     * Intenta autenticar a un usuario basándose en email o DNI.
     *
     * @param string $loginField (Email o DNI)
     * @param string $password
     * @return \App\Models\User|null
     */
    public function verifyCredentials(string $loginField, string $password): ?User
    {
        // 1. Intentar encontrar el usuario por EMAIL o DNI
        $user = User::where('email', $loginField)
                    ->orWhere('dni', $loginField)
                    ->first();

        // 2. Verificar si el usuario fue encontrado y si la contraseña coincide
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }

        return null;
    }
    
}
