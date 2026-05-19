<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin (Email: admin@widia.com | Password: password)
        User::create([
            'name' => 'Widia Admin',
            'email' => 'admin@widia.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Customer (Email: customer@widia.com | Password: password)
        User::create([
            'name' => 'Widia Customer',
            'email' => 'customer@widia.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Call ProductSeeder
        $this->call(ProductSeeder::class);
    }
}
