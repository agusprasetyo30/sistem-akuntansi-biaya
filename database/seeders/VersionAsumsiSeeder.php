<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VersionAsumsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('version_asumsi')->insert([
            'id' => 1,
            'company_code' => 'B000',
            'version' => '2022v1',
            'data_bulan' => '4',
            'awal_periode' => '2022-01-01 00:00:00',
            'akhir_periode' => '2022-04-01 00:00:00',
            'saldo_awal' => '2021-12-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
        ]);

        DB::table('version_asumsi')->insert([
            'id' => 2,
            'company_code' => 'B000',
            'version' => '2022v2',
            'data_bulan' => '5',
            'awal_periode' => '2022-05-01 00:00:00',
            'akhir_periode' => '2022-09-01 00:00:00',
            'saldo_awal' => '2022-04-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
        ]);
    }
}
