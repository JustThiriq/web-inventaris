<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Electronics', 'description' => 'Electronic devices and components', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Clothing', 'description' => 'Apparel and fashion items', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Home & Garden', 'description' => 'Household and gardening supplies', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sports & Outdoors', 'description' => 'Sports equipment and outdoor gear', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Books & Media', 'description' => 'Books, magazines, and media content', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Health & Beauty', 'description' => 'Personal care and beauty products', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Automotive', 'description' => 'Car parts and automotive accessories', 'created_at' => now(), 'updated_at' => now()],
        ]);

    }
}
