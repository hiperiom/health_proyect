<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreService
{
    public function execute(array $data): User  
    {
        return DB::transaction(function () use ($data) {
            
            if (request()->hasFile('avatar')) {
                $data['profile_photo_path'] = request()->file('avatar')->store('user_avatars', 'public');
            }

            $firstName = $this->extractFirstName($data['first_names']);
            $lastName = $this->extractFirstName($data['last_names']);

            $fullName = trim($firstName . ' ' . $lastName);

            $user =   User::create([
                    'name' => $fullName,
                    'dni' => $data['dni'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'profile_photo_path' => $data['profile_photo_path'] ?? NULL,
                ]);

            return $user;
        });
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