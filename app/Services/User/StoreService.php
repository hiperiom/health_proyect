<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreService
{
    public function execute(array $data): User  
    {
        $request = app(Request::class); 
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('user_avatars', 'public');
        }

        $firstName = $this->extractFirstName($data['first_names']);
        $lastName = $this->extractFirstName($data['last_names']);

        $fullName = trim($firstName . ' ' . $lastName);

        $user =   User::create([
                'name' => $fullName,
                'dni' => $data['dni'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'profile_photo_path' => $avatarPath,
            ]);

        $user->assignRole('Paciente');
        //dd($data);
        UserProfile::create([
            'user_id' => $user->id, 
            'first_names' => $data['first_names'],
            'last_names' => $data['last_names'],
            'gender' => $data['gender'],
            'birthday' => date('Y-m-d',strtotime($data['birthday'])),

        ]);
        return $user;
    }
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