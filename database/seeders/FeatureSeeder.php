<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'db' => 'role',
                'keterangan' => 'Role',
            ],
            [
                'db' => 'users',
                'keterangan' => 'User',
            ],
            [
                'db' => 'mapping_role',
                'keterangan' => 'Mapping User Role',
            ],
            [
                'db' => 'management_role',
                'keterangan' => 'Mapping User Akses',
            ],
            [
                'db' => 'company',
                'keterangan' => 'Company',
            ],
            [
                'db' => 'kategori_material',
                'keterangan' => 'Kategori Material',
            ],
            [
                'db' => 'kategori_produk',
                'keterangan' => 'Kategori Produk',
            ],
            [
                'db' => 'kategori_balans',
                'keterangan' => 'Kategori Balans',
            ],
            [
                'db' => 'map_kategori_balans',
                'keterangan' => 'Mapping Kategori Balans',
            ],
            [
                'db' => 'group_account',
                'keterangan' => 'Group Account',
            ],
            [
                'db' => 'gl_account',
                'keterangan' => 'General Ledger',
            ],
            [
                'db' => 'group_account_fc',
                'keterangan' => 'Group Account Fixed Cost',
            ],
            [
                'db' => 'gl_account_fc',
                'keterangan' => 'General Ledger Account Fixed Cost',
            ],
            [
                'db' => 'cost_center',
                'keterangan' => 'Cost Center',
            ],
            [
                'db' => 'material',
                'keterangan' => 'Material',
            ],
            [
                'db' => 'plant',
                'keterangan' => 'Plant',
            ],
            [
                'db' => 'glos_cc',
                'keterangan' => 'Glos CC',
            ],
            [
                'db' => 'tarif',
                'keterangan' => 'Tarif',
            ],
            [
                'db' => 'asumsi_umum',
                'keterangan' => 'Asumsi Umum',
            ],
            [
                'db' => 'kurs',
                'keterangan' => 'Kurs',
            ],
            [
                'db' => 'regions',
                'keterangan' => 'Region',
            ],
            [
                'db' => 'cons_rate',
                'keterangan' => 'Const Rate',
            ],
            [
                'db' => 'saldo_awal',
                'keterangan' => 'Saldo Awal',
            ],
            [
                'db' => 'qty_renprod',
                'keterangan' => 'Kuantiti Rencana Produksi',
            ],
            [
                'db' => 'qty_rendaan',
                'keterangan' => 'Kuantiti Rencana Pengadaan',
            ],
            [
                'db' => 'price_rendaan',
                'keterangan' => 'Price Rencana Pengadaan',
            ],
            [
                'db' => 'zco',
                'keterangan' => 'ZCO',
            ],
            [
                'db' => 'salrs',
                'keterangan' => 'SALR',
            ],
            [
                'db' => 'laba_rugi',
                'keterangan' => 'Laba Rugi',
            ],
            [
                'db' => 'pj_pemakaian',
                'keterangan' => 'Pakai Jual Pemakaian',
            ],
            [
                'db' => 'pj_penjualan',
                'keterangan' => 'Pakai Jual Penjualan',
            ],
            // [
            //     'db' => 'kontrol_proyeksi',
            //     'keterangan' => 'Kontrol Proyeksi ',
            // ],
            [
                'db' => 'balans',
                'keterangan' => 'Balans ',
            ],
            [
                'db' => 'simulasi_proyeksi',
                'keterangan' => 'Simulasi Proyeksi',
            ],
        ];

        foreach ($data as $items) {
            $kode_unik = Uuid::uuid1()->toString();

            Feature::create([
                'kode_unik' => $kode_unik,
                'db' => $items['db'],
                'feature' => strtoupper($items['keterangan']),
                'feature_name' => $items['keterangan'],
            ]);

            DB::table($items['db'])->update([
                'kode_feature' => $kode_unik
            ]);
        }
    }
}
