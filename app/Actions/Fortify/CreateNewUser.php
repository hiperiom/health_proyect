<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
       
        Validator::make(
            $input, 
            [
                'dni' => ['required','numeric','digits_between:6,8 ','unique:users,dni'],
                'email' => ['required','email','unique:users,email'],
                'password' => ['required','string','min:8','max:8'],
                'first_names' => ['required','string','max:100'],
                'last_names' => ['required','string','max:100'],
                'gender' => ['required','string','max:1'],
                'avatar'=> ['nullable','image','mimes:jpeg,png,jpg','max:2048'],
                'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            ],
            [
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
                'avatar.image' => 'El archivo debe ser una imagen.',
                'avatar.mimes' => 'El archivo debe ser un archivo de imagen.',
                'avatar.max' => 'El archivo no debe exceder los 2048 bytes.',
                

            ]
        )->validate();

        $request = app(Request::class); 

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('user_avatars', 'public');
        }

        $firstName = $this->extractFirstName($input['first_names']);
        $lastName = $this->extractFirstName($input['last_names']);

        $fullName = trim($firstName . ' ' . $lastName);

        return DB::transaction(function () use ($input,$fullName,$avatarPath) {
           
            $user =  User::create([
                'name' => $fullName,
                'dni' => $input['dni'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'avatar' => $avatarPath,
            ]);
            $user->assignRole('Paciente');

            UserProfile::create([
                'user_id' => $user->id, 
                'first_names' => $input['first_names'],
                'last_names' => $input['last_names'],
                'gender' => $input['gender'],

            ]);
            return $user;
            
        }); //
       


    }
    /**
     * Extrae la primera palabra de una cadena.
     * @param string $fullNameString
     * @return string
     */
    private function extractFirstName(string $fullNameString): string
    {
        // 1. Limpiar espacios iniciales/finales
        $fullNameString = trim($fullNameString);

        // 2. Dividir la cadena en palabras (usando uno o más espacios como delimitador)
        $words = preg_split('/\s+/', $fullNameString);

        // 3. Devolver la primera palabra si existe, o una cadena vacía
        return $words[0] ?? '';
    }
}
