<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kategori_material')->insert([
            'company_code' => 'B000',
            'kategori_material_name' => 'Nilai Dasar Balans',
            'kategori_material_desc' => 'keterangan nilai dasar balans',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('kategori_material')->insert([
            'company_code' => 'B000',
            'kategori_material_name' => 'Nilai Dasar ZCOHPPDET',
            'kategori_material_desc' => 'keterangan nilai dasar zcohppdet',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('kategori_material')->insert([
            'company_code' => 'B000',
            'kategori_material_name' => 'Nilai Dasar Stock',
            'kategori_material_desc' => 'keterangan nilai dasar stock',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('kategori_material')->insert([
            'company_code' => 'B000',
            'kategori_material_name' => 'Kantong',
            'kategori_material_desc' => 'keterangan kantong',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
