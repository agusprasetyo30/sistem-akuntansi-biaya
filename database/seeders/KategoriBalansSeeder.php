<?php

namespace Database\Seeders;

use App\Models\KategoriBalans;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KategoriBalansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriBalans::create([
            'kategori_balans' => 'Saldo Awal',
            'kategori_balans_desc' => 'Saldo Awal',
            'company_code'=> 'B000',
            'order_view'=> 1,
            'created_by'=> 1,
            'updated_by'=> 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        KategoriBalans::create([
            'kategori_balans' => 'Pengadaan',
            'kategori_balans_desc' => 'Pengadaan',
            'company_code'=> 'B000',
            'order_view'=> 4,
            'created_by'=> 1,
            'updated_by'=> 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        KategoriBalans::create([
            'kategori_balans' => 'Tersedia',
            'kategori_balans_desc' => 'Tersedia',
            'company_code'=> 'B000',
            'order_view'=> 5,
            'created_by'=> 1,
            'updated_by'=> 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        KategoriBalans::create([
            'kategori_balans' => 'Pemakaian',
            'kategori_balans_desc' => 'Pemakaian',
            'company_code'=> 'B000',
            'order_view'=> 6,
            'created_by'=> 1,
            'updated_by'=> 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        KategoriBalans::create([
            'kategori_balans' => 'Penjualan',
            'kategori_balans_desc' => 'Penjualan',
            'company_code'=> 'B000',
            'order_view'=> 7,
            'created_by'=> 1,
            'updated_by'=> 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);

        KategoriBalans::create([
            'kategori_balans' => 'Saldo Akhir',
            'kategori_balans_desc' => 'Saldo Akhir',
            'company_code'=> 'B000',
            'order_view'=> 8,
            'created_by'=> 1,
            'updated_by'=> 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
