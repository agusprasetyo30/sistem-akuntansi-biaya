<?php

namespace Database\Seeders;

use App\Models\Regions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/json/region.json");
        $region = json_decode($json);

        foreach ($region as $items){
            Regions::create([
                'region_name' => $items->Country_ID,
                'region_desc' => $items->Country_Name,
                'is_active' => 't',
                'latitude' => '1',
                'longtitude' => '1',
                'created_by' => '1',
                'updated_by' => '1',
            ]);
        }
    }
}
