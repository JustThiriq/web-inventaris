<?php

namespace Database\Seeders;

use App\Models\Bidang;
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

        $bidangs = Bidang::all();
        foreach ($bidangs as $bidang) {

            $namaBidang = str_replace(' ', '_', strtolower($bidang->name));

            $userRole = Role::where('slug', 'warehouse')->first();
            User::create([
                'name' => 'Warehouse Manager '.$namaBidang,
                'email' => 'warehouse_'.strtolower($namaBidang).'@example.com',
                'password' => Hash::make('password'),
                'role_id' => $userRole->id,
                'bidang_id' => $bidang->id,
            ]);

            $userRole = Role::where('slug', 'user')->first();
            User::create([
                'name' => 'Warehouse Staff '.$namaBidang,
                'email' => 'warehouse_staff_'.strtolower($namaBidang).'@example.com',
                'password' => Hash::make('password'),
                'role_id' => $userRole->id,
                'bidang_id' => $bidang->id,
            ]);
        }
    }
}
