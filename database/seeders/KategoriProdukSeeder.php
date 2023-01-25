<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Urea',
            'ZA',
            'SP-36',
            'Phonska',
            'Petroganik',
            'NPK Kebomas',
            'DAP',
            'Pupuk ZK',
            'Phonska Oca',
            'Dolomit',
            'Amoniak Lokal',
            'Asam Sulfat Lokal',
            'Asam Fosfat jual',
            'CO2 Cair',
            'Dry Ice (CO2 Padat)',
            'ALF3 Lokal',
            'ALF3 Ekspor',
            'Crude Gypsum/Petrocas (kantong) Lokal',
            'Gypsum/NCG (curah) Lokal',
            'Raw Gypsum',
            'Purified Gypsum Lokal',
            'Kapur Pertanian',
            'Asam Chlorida (HCl)',
            'Ocamix Perkebunan',
            'Nitrogen',
            'Hidrogen',
            'Air - Hardwater',
            'Air - Air Demin',
            'Steam',
            'Mixtro',
            'Sulphur',
        ];

        for ($i = 1 ; $i < count($data) ; $i++){
            DB::table('kategori_produk')->insert([
                'company_code' => 'B000',
                'kategori_produk_name' => strtoupper($data[$i-1]),
                'kategori_produk_desc' => $data[$i-1],
                'is_active' => 't',
                'created_at' => Carbon::now()->format('Y-m-d'),
                'updated_at' => Carbon::now()->format('Y-m-d'),
                'created_by' => '1',
            ]);
        }
    }
}
