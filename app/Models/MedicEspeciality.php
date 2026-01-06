<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicEspeciality extends Model
{
    /** @use HasFactory<\Database\Factories\MedicEspecialityFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'medic_especialities';
    protected $fillable = [
        'name',
        'description',
    ];  
}
