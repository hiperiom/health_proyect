<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Medic extends Model
{
    /** @use HasFactory<\Database\Factories\MedicFactory> */
    use HasFactory;
    protected $table = 'users';
    protected $hidden = [];

    public function profile(): HasOne
    {
        // HasOne indica que un usuario tiene un perfil
        // Laravel buscará automáticamente 'user_id' en la tabla user_profiles
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }
}
