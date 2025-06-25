<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
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

        // Insert Warehouses
        DB::table('warehouses')->insert([
            ['name' => 'Main Distribution Center', 'location' => '123 Industrial Blvd, City A', 'manager_name' => 'John Smith', 'phone' => '+1-555-0101', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'West Coast Facility', 'location' => '456 Warehouse Ave, City B', 'manager_name' => 'Sarah Johnson', 'phone' => '+1-555-0102', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'East Coast Hub', 'location' => '789 Storage St, City C', 'manager_name' => 'Mike Davis', 'phone' => '+1-555-0103', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Central Processing', 'location' => '321 Logistics Dr, City D', 'manager_name' => 'Lisa Wilson', 'phone' => '+1-555-0104', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Northern Branch', 'location' => '654 Supply Chain Rd, City E', 'manager_name' => 'Tom Brown', 'phone' => '+1-555-0105', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        DB::table('categories')->truncate();
        DB::table('warehouses')->truncate();
    }
};