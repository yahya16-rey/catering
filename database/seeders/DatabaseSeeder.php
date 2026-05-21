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
        // Create Admin (Email: admin@dinda.com | Password: password)
        User::create([
            'name' => 'Dinda Admin',
            'email' => 'admin@dinda.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Customer (Email: customer@dinda.com | Password: password)
        User::create([
            'name' => 'Dinda Customer',
            'email' => 'customer@dinda.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // Call ProductSeeder
        $this->call(ProductSeeder::class);
    }
}
