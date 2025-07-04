<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert Warehouses
        DB::table('warehouses')->insert([
            ['name' => 'Main Distribution Center', 'location' => '123 Industrial Blvd, City A', 'manager_name' => 'John Smith', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'West Coast Facility', 'location' => '456 Warehouse Ave, City B', 'manager_name' => 'Sarah Johnson', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'East Coast Hub', 'location' => '789 Storage St, City C', 'manager_name' => 'Mike Davis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Central Processing', 'location' => '321 Logistics Dr, City D', 'manager_name' => 'Lisa Wilson', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Northern Branch', 'location' => '654 Supply Chain Rd, City E', 'manager_name' => 'Tom Brown', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
