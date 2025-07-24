<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Peserta',
            'email' => 'peserta@example.com',
            'password' => Hash::make('password'),
            'role' => 'peserta',
        ]);
        User::create([
            'name' => 'Pembimbing Instansi',
            'email' => 'instansi@example.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing_instansi',
        ]);
        User::create([
            'name' => 'Pembimbing Kominfo',
            'email' => 'kominfo@example.com',
            'password' => Hash::make('password'),
            'role' => 'pembimbing_kominfo',
        ]);
    }
}
