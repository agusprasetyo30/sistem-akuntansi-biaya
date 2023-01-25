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
            'group_account_code' => '2000',
            'group_account_desc' => 'Gaji Kesejahteraan',
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

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '6000',
            'group_account_desc' => 'Biaya Overhead langsung Suku Cadang',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '7000',
            'group_account_desc' => 'Biaya Overhead langsung pemeliharaan',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '8000',
            'group_account_desc' => 'Biaya Overhead langsung Asuransi',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '9000',
            'group_account_desc' => 'Biaya Overhead langsung jasa',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '10000',
            'group_account_desc' => 'Biaya Overhead langsung sewa',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '11000',
            'group_account_desc' => 'Biaya Overhead langsung amortisasi & penyusutan',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '12000',
            'group_account_desc' => 'Biaya Overhead langsung pengantongan',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '13000',
            'group_account_desc' => 'Biaya Overhead langsung handling',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '14000',
            'group_account_desc' => 'Biaya Overhead langsung lain-lain',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '15000',
            'group_account_desc' => 'Biaya Overhead tidak langsung gaji',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '16000',
            'group_account_desc' => 'Biaya Overhead tidak langsung pemeliharaan',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '17000',
            'group_account_desc' => 'Biaya Overhead tidak langsung asuransi',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '18000',
            'group_account_desc' => 'Biaya Overhead tidak langsung jasa',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '19000',
            'group_account_desc' => 'Biaya Overhead tidak langsung sewa',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '20000',
            'group_account_desc' => 'Biaya Overhead tidak langsung amortisasi & penyusutan',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);

        DB::table('group_account')->insert([
            'company_code' => 'B000',
            'group_account_code' => '21000',
            'group_account_desc' => 'Biaya Overhead tidak langsung lain-lain',
            'is_active' => 't',
            'created_at' => '2022-09-30 16:31:42',
            'created_by' => '1',
        ]);
    }
}
