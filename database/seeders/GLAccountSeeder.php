<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GLAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gl_account')->insert([
            'company_code' => 'B000',
            'gl_account' => '610110000',
            'gl_account_desc' => 'BAHAN BAKU',
            'group_account_code' => '1000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
        DB::table('gl_account')->insert([
            'company_code' => 'B000',
            'gl_account' => '610120000',
            'gl_account_desc' => 'SUSUT BAHAN BAKU',
            'group_account_code' => '1000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
        DB::table('gl_account')->insert([
            'company_code' => 'B000',
            'gl_account' => '610210000',
            'gl_account_desc' => 'UTILITAS',
            'group_account_code' => '5000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
        DB::table('gl_account')->insert([
            'company_code' => 'B000',
            'gl_account' => '610220000',
            'gl_account_desc' => 'BAHAN PEMBANTU',
            'group_account_code' => '3000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
        DB::table('gl_account')->insert([
            'company_code' => 'B000',
            'gl_account' => '610230000',
            'gl_account_desc' => 'AIR BAKU',
            'group_account_code' => '4000',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
