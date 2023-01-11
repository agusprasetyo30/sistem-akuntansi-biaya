<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cost_center')->insert([
            'cost_center' => 'B004123000',
            'cost_center_desc' => 'BAG ADM DISTRIBUSI',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('cost_center')->insert([
            'cost_center' => 'B003312000',
            'cost_center_desc' => 'BAG ADM PELABUHAN',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('cost_center')->insert([
            'cost_center' => 'B006121000',
            'cost_center_desc' => 'BAG ADM SDM&HRIS',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('cost_center')->insert([
            'cost_center' => 'B002111000',
            'cost_center_desc' => 'BAG AMONIAK',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('cost_center')->insert([
            'cost_center' => 'B002322000',
            'cost_center_desc' => 'BAG ASAM FOSFAT IIIA',
            'company_code' => 'B000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
