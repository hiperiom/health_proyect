<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'dni' => '00000000',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
        ]);
        $admin->assignRole('admin');

        $patient = User::factory()->create([
            'dni' => '00000001',
            'email' => 'patient@text.com',
            'password' => Hash::make('patient'),
        ]);
        $patient->assignRole('patient');
    }
}
