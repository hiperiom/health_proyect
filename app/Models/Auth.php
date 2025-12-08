<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Auth extends Model
{
    /** @use HasFactory<\Database\Factories\AuthFactory> */
    use HasApiTokens, HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'dni',
        'email',
        'password',
    ];
}
