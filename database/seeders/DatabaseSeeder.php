<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@perpustakaan.test',
        ], [
            'name' => 'Admin Perpustakaan',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        User::updateOrCreate([
            'email' => 'user@perpustakaan.test',
        ], [
            'name' => 'Anggota Perpustakaan',
            'password' => 'password',
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);
    }
}
