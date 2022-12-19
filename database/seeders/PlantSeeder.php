<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plant')->insert([
            'plant_code' => 'B001',
            'plant_desc' => 'Plant Gudang Lini 1 Pemasaran',
            'is_active' => 't',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('plant')->insert([
            'plant_code' => 'B002',
            'plant_desc' => 'Plant Gudang Lini 2 Pemasaran',
            'is_active' => 't',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('plant')->insert([
            'plant_code' => 'B601',
            'plant_desc' => 'Pergudangan dan Pemeliharaan',
            'is_active' => 't',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
