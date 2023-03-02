<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ParameterSimulasiDataTable;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class KontrolProyeksiController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.kontrol_proyeksi.index');
    }

    public function uptodate(){

        DB::transaction(function (){
//            Feature::delete();

            $data = [
                [
                    'db' => 'company',
                    'keterangan' => 'Company',
                ],
                [
                    'db' => 'users',
                    'keterangan' => 'User',
                ],
                [
                    'db' => 'version_asumsi',
                    'keterangan' => 'Versi Asumsi',
                ],
                [
                    'db' => 'plant',
                    'keterangan' => 'Plant',
                ],[
                    'db' => 'kategori_material',
                    'keterangan' => 'Kategori Material',
                ],
                [
                    'db' => 'kategori_produk',
                    'keterangan' => 'Kategori Produk',
                ],
                [
                    'db' => 'group_account',
                    'keterangan' => 'Group Account',
                ],
                [
                    'db' => 'gl_account',
                    'keterangan' => 'General Ledger',
                ],[
                    'db' => 'cost_center',
                    'keterangan' => 'Cost Center',
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
                    'db' => 'asumsi_umum',
                    'keterangan' => 'Asumsi Umum',
                ],[
                    'db' => 'saldo_awal',
                    'keterangan' => 'Saldo Awal',
                ],
                [
                    'db' => 'qty_renprod',
                    'keterangan' => 'Kuantiti Rencana Produksi',
                ],
                [
                    'db' => 'price_rendaan',
                    'keterangan' => 'Price Rencana Pengadaan',
                ],
                [
                    'db' => 'qty_rendaan',
                    'keterangan' => 'Kuantiti Rencana Pengadaan',
                ],[
                    'db' => 'role',
                    'keterangan' => 'Role',
                ],
                [
                    'db' => 'management_role',
                    'keterangan' => 'Management Role',
                ],
                [
                    'db' => 'kurs',
                    'keterangan' => 'Kurs',
                ],
                [
                    'db' => 'group_account_fc',
                    'keterangan' => 'Group Account Fixed Cost',
                ],[
                    'db' => 'gl_account_fc',
                    'keterangan' => 'General Ledger Account Fixed Cost',
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
                    'db' => 'pj_pemakaian',
                    'keterangan' => 'Pakai Jual Pemakaian',
                ],[
                    'db' => 'pj_penjualan',
                    'keterangan' => 'Pakai Jual Penjualan',
                ],
                [
                    'db' => 'laba_rugi',
                    'keterangan' => 'Laba Rugi',
                ],
                [
                    'db' => 'glos_cc',
                    'keterangan' => 'Glos CC',
                ],
                [
                    'db' => 'map_kategori_balans',
                    'keterangan' => 'Mapping Kategori Balans',
                ],[
                    'db' => 'temp_proyeksi',
                    'keterangan' => 'Temp Proyeksi',
                ],
                [
                    'db' => 'simulasi_proyeksi',
                    'keterangan' => 'Simulasi Proyeksi',
                ],
                [
                    'db' => 'balans',
                    'keterangan' => 'Balans ',
                ],
                [
                    'db' => 'tarif',
                    'keterangan' => 'Tarif',
                ]
            ];

            foreach ($data as $items){
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
        });

        return view('pages.kontrol_proyeksi.index');
    }

    public function get_data(Request $request, ParameterSimulasiDataTable $parameterSimulasiDataTable){
        return $parameterSimulasiDataTable->render('pages.kontrol_proyeksi.index');
    }
}
