<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '1000',
            'group_account_desc' => 'Biaya Bahan Baku',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '3000',
            'group_account_desc' => 'Biaya Bahan Baku Penolong',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '4000',
            'group_account_desc' => 'Biaya Air Baku Lainnya',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '5000',
            'group_account_desc' => 'Biaya Utilitas',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
