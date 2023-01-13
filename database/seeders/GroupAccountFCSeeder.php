<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GroupAccountFCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/json/ga_fc.json");
        $gaFC= json_decode($json);

        foreach ($gaFC as $items){
            DB::table('group_account_fc')->insert([
                'company_code' => 'B000',
                'group_account_fc' => $items->Kelompok_Acc,
                'group_account_fc_desc' => $items->Kelompok_Acc_Desc,
                'created_at' => '2022-09-30 16:31:42',
                'created_by' => '1',
            ]);
        }
    }
}
