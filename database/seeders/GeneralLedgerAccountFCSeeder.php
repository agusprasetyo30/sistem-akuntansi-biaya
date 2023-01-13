<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GeneralLedgerAccountFCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/json/gl_fc.json");
        $glFC= json_decode($json);

        foreach ($glFC as $items){
            DB::table('gl_account_fc')->insert([
                'company_code' => 'B000',
                'gl_account_fc' => $items->GL_Acc,
                'gl_account_fc_desc' => $items->GL_Acc_Desc,
                'group_account_fc' => $items->Kelompok_Acc,
                'created_at' => '2022-09-30 16:31:42',
                'created_by' => '1',
            ]);
        }
    }
}
