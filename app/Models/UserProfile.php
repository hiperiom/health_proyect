<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory;

    protected $table = 'user_profiles';
    protected $fillable = [
        'first_names',
        'last_names',
        'nacionality',
        'dni_picture_url',
        'birthday',
        'gender',
        'picture_url',
        'movil_phone',
        'local_phone',
        'user_id',
    ];
    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

