<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => [
                'required',
                // acepta: 6-8 dígitos numéricos OR formato email
                'regex:/^(?:\d{6,8}|[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,})$/'
            ],
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required' => 'El identificador es obligatorio.',
            'identifier.regex' => 'El identificador debe ser un correo válido o un número de 6 a 8 dígitos.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }
}
