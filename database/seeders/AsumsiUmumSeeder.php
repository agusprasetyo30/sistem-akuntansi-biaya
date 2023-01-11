<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsumsiUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 1,
            'usd_rate' => '10500',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-01-01 00:00:00',
            'saldo_awal' => '2021-12-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 1,
            'usd_rate' => '10900',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-02-01 00:00:00',
            'saldo_awal' => '2022-01-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('asumsi_umum')->insert([

            'company_code' => 'B000',
            'version_id' => 1,
            'usd_rate' => '11100',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-03-01 00:00:00',
            'saldo_awal' => '2022-02-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 1,
            'usd_rate' => '11400',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-04-01 00:00:00',
            'saldo_awal' => '2022-03-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 2,
            'usd_rate' => '11500',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-05-01 00:00:00',
            'saldo_awal' => '2022-04-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 2,
            'usd_rate' => '11300',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-06-01 00:00:00',
            'saldo_awal' => '2022-05-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 2,
            'usd_rate' => '11400',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-07-01 00:00:00',
            'saldo_awal' => '2022-06-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 2,
            'usd_rate' => '11500',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-08-01 00:00:00',
            'saldo_awal' => '2022-07-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('asumsi_umum')->insert([
            'company_code' => 'B000',
            'version_id' => 2,
            'usd_rate' => '11600',
            'adjustment' => '2',
            'inflasi' => '2',
            'month_year' => '2022-09-01 00:00:00',
            'saldo_awal' => '2022-08-01 00:00:00',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
