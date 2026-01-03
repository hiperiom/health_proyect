<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

        public function rules(): array
    {
        $userId = ($this->route('user'));
      
        return [
            'dni' => "required|numeric|digits_between:6,8 |unique:users,dni,{$userId}",
            'email' => "required|email|unique:users,email,{$userId}",
            'first_names' => 'required|string|max:100',
            'last_names' => 'required|string|max:100',
            'gender' => 'required|string|max:1',
            'birthday' => 'nullable|date',
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
            'first_names.required' => 'Los nombres son obligatorios.',
            'first_names.string' => 'Los nombres deben ser una cadena de texto.',
            'first_names.max' => 'Los nombres no deben exceder los 100 caracteres.',
            'last_names.required' => 'Los apellidos son obligatorios.',
            'last_names.string' => 'Los apellidos deben ser una cadena de texto.',
            'last_names.max' => 'Los apellidos no deben exceder los 100 caracteres.',
            'gender.required' => 'El género es obligatorio.',
            'gender.char' => 'El género debe ser un carácter.',
            'gender.max' => 'El género no debe exceder un carácter.',
        ];
    }
}