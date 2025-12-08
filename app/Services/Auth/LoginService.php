<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
class LoginService
{
    /**
     * Attempt to login a user by email or name and password.
     *
     * @param string $identifier Email or name to identify the user
     * @param string $password
     * @return array{user: User, token: string}
     * @throws \Exception When credentials are invalid
     */
    public function login(string $identifier, string $password): array
    {
   
        // 1. Intentar encontrar el usuario por email o dni para construir el array de credenciales.
        // Asumiendo que el campo 'email' es la columna predeterminada.
        
        $user = User::where('email', $identifier)
                ->orWhere('dni', $identifier)
                ->first();
        
        // 2. Si no se encuentra el usuario, lanza la excepción de autenticación de inmediato.
        if (!$user) {
            throw new AuthenticationException('Credenciales inválidas.');
        }

        // 3. Usar el método attempt() con las credenciales, usando el campo del identificador encontrado.
        // Esto verifica el hash de forma segura y mitiga Timing Attacks.
        $loginSuccessful = Auth::attempt([
            $user->email === $identifier ? 'email' : 'dni' => $identifier,
            'password' => $password,
        ]);
     
        if (!$loginSuccessful) {
             throw new AuthenticationException('Credenciales inválidas.');
        }

        // Auth::user() ahora apunta al usuario autenticado.
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
