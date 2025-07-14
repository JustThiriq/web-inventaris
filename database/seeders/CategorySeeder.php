<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Insert Categories
        DB::table('categories')->insert([
            ['name' => 'Consumable', 'description' => 'Items that are used up or consumed during use', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Non Consumable', 'description' => 'Items that are not used up and can be reused', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
