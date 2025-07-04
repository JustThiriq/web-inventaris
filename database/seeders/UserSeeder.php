<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        User::create([
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);
    }
}
