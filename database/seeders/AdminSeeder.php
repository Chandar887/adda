<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@123'), // Replace 'password' with the actual password
            'role' => 'admin', // Add a flag to identify admin users (you can adjust your User model for this)
        ]);
    }
}
