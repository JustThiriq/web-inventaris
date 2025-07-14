<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidangs = [
            'Mekanik',
            'Elektronik',
            'Kendaraan',
            'Konstruksi',
            'Teknologi Informasi',
            'Telekomunikasi',
        ];

        foreach ($bidangs as $bidang) {
            DB::table('bidangs')->insert([
                'name' => $bidang,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
