<?php

namespace App\Http\Controllers;

use App\DataTables\Master\KelengkapanBiayaTetapDataTable;
use App\DataTables\Master\KelengkapanBOMDataTable;
use App\DataTables\Master\KelengkapanHargaMaterialDataTable;
use App\DataTables\Master\ParameterSimulasiDataTable;
use App\Models\Asumsi_Umum;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class KontrolProyeksiController extends Controller
{
    public function index(Request $request)
    {
        $this->uptodate();

        $data_company = User::select('users.company_code', 'company.company_name')
            ->where('id', auth()->user()->id)
            ->leftjoin('company', 'company.company_code', '=', 'users.company_code')
            ->first();
        return view('pages.kontrol_proyeksi.index', compact('data_company'));
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
                ]
//                ,[
//                    'db' => 'role',
//                    'keterangan' => 'Role',
//                ]
//                ,
//                [
//                    'db' => 'management_role',
//                    'keterangan' => 'Management Role',
//                ]
                ,
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

                Feature::updateOrCreate([
                    'db' => $items['db'],
                ],[
                    'kode_unik' => $kode_unik,
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

    public function get_data(Request $request, ParameterSimulasiDataTable $parameterSimulasiDataTable, KelengkapanBiayaTetapDataTable $kelengkapanBiayaTetapDataTable, KelengkapanHargaMaterialDataTable $kelengkapanHargaMaterialDataTable, KelengkapanBOMDataTable $kelengkapanBOMDataTable){
        $asumsi_data = Asumsi_Umum::where('id',$request->asumsi)
            ->get();

        $asumsi = $asumsi_data->pluck('id')->all();
        $temp = explode(' ', $asumsi_data->pluck('month_year')->first());
        $date = $temp[0];

        if ($request->data == 'index'){
            return $parameterSimulasiDataTable->with(['company' => $request->company, 'asumsi' => $asumsi, 'date' => $date, 'versi' => $request->versi])->render('pages.kontrol_proyeksi.index');
        }elseif ($request->data == 'kelengkapan_biaya_tetap'){
            return $kelengkapanBiayaTetapDataTable->with(['company' => $request->company, 'asumsi' => $asumsi, 'date' => $date, 'versi' => $request->versi])->render('pages.kontrol_proyeksi.index');
        }elseif ($request->data == 'kelengkapan_harga_material'){
            return $kelengkapanHargaMaterialDataTable->with(['company' => $request->company, 'asumsi' => $asumsi, 'date' => $date, 'versi' => $request->versi])->render('pages.kontrol_proyeksi.index');
        }elseif ($request->data == 'kelengkapan_bom'){
            return $kelengkapanBOMDataTable->with(['company' => $request->company, 'asumsi' => $asumsi, 'date' => $date, 'versi' => $request->versi])->render('pages.kontrol_proyeksi.index');
        }

    }
}
