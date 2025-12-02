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
        // Create only the administrator account. Regular users are not created by seeder.
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin',
            'password' => bcrypt('9999'),
            'role' => 'admin',
        ]);

        // Keep factory example commented
        // User::factory(8)->create();
    }
}
