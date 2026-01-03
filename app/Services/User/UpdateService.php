<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UpdateService
{

    public function execute(User $user, array $data): User
    {
        $request = app(Request::class); 
        $avatarPath = null;
        if (request()->hasFile('avatar')) {
            $data['profile_photo_path'] = request()->file('avatar')->store('user_avatars', 'public');
        }

        $firstName = $this->extractFirstName($data['first_names']);
        $lastName = $this->extractFirstName($data['last_names']);

        $fullName = trim($firstName . ' ' . $lastName);

        $user->update([
            'name' => $fullName,
            'dni' => $data['dni'],
            'email' => $data['email'],
            'profile_photo_path' => $data['profile_photo_path'] ?? $user->profile_photo_path,
        ]);
            
        $user->profile()->update([
            'first_names' => $data['first_names'],
            'last_names' => $data['last_names'],
            'gender' => $data['gender'],
            'birthday' => $data['birthday'],

        ]);

        return $user->fresh();
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