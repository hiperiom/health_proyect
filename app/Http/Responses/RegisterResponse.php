<?php
// app/Http/Responses/RegisterResponse.php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // 1. Cerramos la sesión que Fortify abre automáticamente
        Auth::logout();

        // 2. Redirigimos al login con un mensaje de éxito
        return redirect()->route('login')
            ->with('status', 'Registro exitoso. Por favor, inicia sesión.');
    }
}