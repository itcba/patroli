<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create two explicit users: admin and regular user
        // Password for both users: password
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin',
            'password' => bcrypt('9999'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'name' => 'Security',
            'email' => 'security',
            'password' => bcrypt('1111'),
            'role' => 'user',
        ]);

        // Keep factory example commented
        // User::factory(8)->create();
    }
}
