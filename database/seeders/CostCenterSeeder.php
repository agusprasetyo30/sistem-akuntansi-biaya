<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CostCenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/json/cost_center.json");
        $cost_center= json_decode($json);

        foreach ($cost_center as $items){
            DB::table('cost_center')->insert([
                'cost_center' => $items->CostCenter,
                'cost_center_desc' => $items->Deskripsi,
                'company_code' => 'B000',
                'created_at' => '2022-09-30 16:31:42',
                'created_by' => '1',
            ]);
        }
    }
}
