<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert([
            'region_name' => 'Indonesia',
            'region_desc' => 'Indonesia',
            'is_active' => 't',
            'latitude' => '1',
            'longtitude' => '1',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
        
        DB::table('regions')->insert([
            'region_name' => 'China',
            'region_desc' => 'China',
            'is_active' => 't',
            'latitude' => '2',
            'longtitude' => '2',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
