<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'dni' => 'required|numeric|digits_between:60,80 |unique:users,dni',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:8',
            'first_names' => 'required|string|max:100',
            'last_names' => 'required|string|max:100',
            'gender' => 'required|string|max:1',
            'profile_photo_path'=> 'required|image|mimes:jpeg,png,jpg|max:2048',
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
            'first_names.required' => 'Los nombres son obligatorios.',
            'first_names.string' => 'Los nombres deben ser una cadena de texto.',
            'first_names.max' => 'Los nombres no deben exceder los 100 caracteres.',
            'last_names.required' => 'Los apellidos son obligatorios.',
            'last_names.string' => 'Los apellidos deben ser una cadena de texto.',
            'last_names.max' => 'Los apellidos no deben exceder los 100 caracteres.',
            'gender.required' => 'El género es obligatorio.',
            'gender.char' => 'El género debe ser un carácter.',
            'gender.max' => 'El género no debe exceder un carácter.',
            'profile_photo_path.image' => 'El archivo debe ser una imagen.',
            'profile_photo_path.mimes' => 'El archivo debe ser un archivo de imagen.',
            'profile_photo_path.max' => 'El archivo no debe exceder los 2048 bytes.',
            

        ];
    }
}
