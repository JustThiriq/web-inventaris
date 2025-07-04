<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);
        Role::create([
            'name' => 'Common User',
            'slug' => 'user',
        ]);
    }
}
