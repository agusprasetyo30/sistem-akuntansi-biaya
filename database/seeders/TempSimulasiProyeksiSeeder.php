<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TempSimulasiProyeksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/json/temp_proyeksi.json");
        $region = json_decode($json);

        foreach ($region as $items) {
            DB::table('temp_proyeksi')->insert([
                'id' => $items->id,
                'proyeksi_no' => $items->proyeksi_no,
                'proyeksi_name' => $items->proyeksi_name,
                'created_at' => '2022-09-30 16:31:42',
                'created_by' => '1',
                'updated_by' => '1',
            ]);
        }
    }
}
