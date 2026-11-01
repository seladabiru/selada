<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    User::create([
        'name' => 'Admin OSIS',
        'nis' => 'admin',
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);

    for ($i = 1; $i <= 10; $i++) {
        User::create([
            'name' => fake()->name(),
            'nis' => '14144' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'kelas' => 'XII RPL ' . rand(1, 2),
            'role' => 'siswa',
            'password' => Hash::make('password'),
        ]);
    }
}
}
