<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dni' => 'required|numeric|digits_between:6,8 |unique:users,dni',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:8',
        ];
    }

    public function messages(): array
    {
        return [
            'dni.required' => 'El Documento de Identidad es obligatorio.',
            'dni.numeric' => 'El Documento de Identidad debe ser numérico.',
            'dni.digits_between' => 'El Documento de Identidad debe tener entre 6 y 8 dígitos.',
            'dni.unique' => 'El Documento de Identidad ya está registrado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.max' => 'La contraseña no debe tener más de 8 caracteres.',
        ];
    }
}
