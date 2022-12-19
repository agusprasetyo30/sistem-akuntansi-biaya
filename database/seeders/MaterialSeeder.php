<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('material')->insert([
            'company_code' => 'B000',
            'material_code' => '2000000',
            'material_name' => 'Ammonia',
            'material_desc' => 'keterangan ammonia',
            'kategori_material_id' => '1',
            'group_account_code' => '1000',
            'material_uom' => 'ton',
            'is_active' => 't',
            'is_dummy' => 'f',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('material')->insert([
            'company_code' => 'B000',
            'material_code' => '1000005',
            'material_name' => 'Cooling Water Area',
            'material_desc' => 'keterangan cooling water area',
            'kategori_material_id' => '2',
            'group_account_code' => '1000',
            'material_uom' => 'ton',
            'is_active' => 't',
            'is_dummy' => 'f',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('material')->insert([
            'company_code' => 'B000',
            'material_code' => '4000006',
            'material_name' => 'Aluminium Sulphate',
            'material_desc' => 'keterangan aluminium sulphate',
            'kategori_material_id' => '3',
            'group_account_code' => '3000',
            'material_uom' => 'ton',
            'is_active' => 't',
            'is_dummy' => 'f',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
