<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateCustomNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // 1. Extraer el primer nombre y el primer apellido
        $firstName = $this->extractFirstName($input['first_names']);
        $lastName = $this->extractFirstName($input['last_names']);

        // 2. Concatenar para crear el nombre completo para la tabla 'users'
        $fullName = trim($firstName . ' ' . $lastName);

        return DB::transaction(function () use ($input,$fullName) {
            //dd($input);
            $user =  User::create([
                'name' => $fullName,
                'dni' => $input['dni'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

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
