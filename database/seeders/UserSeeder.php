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
        // Create default admin
        User::create([
            'name' => 'Admin Warehouse',
            'email' => 'admin@warehouse.com',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create sample user
        User::create([
            'name' => 'User Gudang',
            'email' => 'user@warehouse.com',
            'role' => 'user',
            'is_active' => true,
            'password' => Hash::make('user123'),
            'email_verified_at' => now(),
        ]);

        // Create additional users for testing
        User::factory(5)->create([
            'role' => 'user',
            'is_active' => true,
        ]);
    }
}
