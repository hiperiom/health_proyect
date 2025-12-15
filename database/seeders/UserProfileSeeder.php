<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserProfile::factory()->create([
            'user_id' => 1,
            'first_names' => 'admin',
            'last_names' => 'admin',
     
        ]);
        UserProfile::factory()->create([
            'user_id' => 2,
            'first_names' => 'patient',
            'last_names' => 'patient',
        ]);
    }
}
