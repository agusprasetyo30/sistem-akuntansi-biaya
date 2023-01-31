<?php

namespace Database\Seeders;

use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MappingMaterialBallansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $material = Material::where('kategori_material_id', 1)->get();
        foreach ($material as $items){
            $bulk = $this->mapping($items->material_code);

            DB::table('map_kategori_balans')->insert($bulk);
        }
    }

    public function mapping($material_code){
        $mapping = [
            [
                'material_code' => $material_code,
                'kategori_balans_id' => 1,
                'company_code' => 'B000',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $material_code,
                'kategori_balans_id' => 2,
                'company_code' => 'B000',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $material_code,
                'kategori_balans_id' => 3,
                'company_code' => 'B000',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $material_code,
                'kategori_balans_id' => 4,
                'company_code' => 'B000',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $material_code,
                'kategori_balans_id' => 5,
                'company_code' => 'B000',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        return $mapping;
    }
}
