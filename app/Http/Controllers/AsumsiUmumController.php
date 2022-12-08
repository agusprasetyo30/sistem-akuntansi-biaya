<?php

namespace App\Http\Controllers;

use App\DataTables\Master\AsumsiUmumDataTable;
use App\Models\Asumsi_Umum;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsumsiUmumController extends Controller
{
    public function index(Request $request, AsumsiUmumDataTable $asumsiUmumDataTable)
    {
        if ($request->data == 'index') {
            return $asumsiUmumDataTable->render('pages.master.asumsi_umum.index');
        }
        return view('pages.master.asumsi_umum.index');
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                "versi" => 'required',
                "jumlah_bulan" => 'required',
                "start_date" => 'required',
                "asumsi" => 'required',
            ]);

            DB::transaction(function () use ($request){
                $lenght_data = count($request->asumsi);

                foreach ($request->asumsi as $key=>$items){
                    $saldo_awal = Carbon::createFromFormat('m/Y', $request->asumsi[$key]['peride_month'])->subMonth()->format('Y-m-01 00:00:00');
                    $month_year = Carbon::createFromFormat('m/Y', $items['peride_month'])->format('Y-m-01 00:00:00');

                    if ($key==0){
                        $awal_periode = Carbon::createFromFormat('m/Y', $request->asumsi[0]['peride_month'])->format('Y-m-01 00:00:00');
                        $akhir_periode = Carbon::createFromFormat('m/Y', $request->asumsi[$lenght_data - 1]['peride_month'])->format('Y-m-01 00:00:00');

                        $input_versi['version'] = $request->versi;
                        $input_versi['data_bulan'] = $request->jumlah_bulan;
                        $input_versi['awal_periode'] = $awal_periode;
                        $input_versi['akhir_periode'] = $akhir_periode;
                        $input_versi['saldo_awal'] = $saldo_awal;
                        $input_versi['company_code'] = 'B000';
                        $versi = Version_Asumsi::create($input_versi);
                    }
                    $input['version_id'] = $versi->id;
                    $input['usd_rate'] = (double) str_replace(',','.',str_replace('.','',str_replace('Rp ', '', $items['kurs'])));
                    $input['adjustment'] = (double) $items['adjustment'];
                    $input['month_year'] = $month_year;
                    $input['saldo_awal'] = $saldo_awal;
                    $input['company_code'] = 'B000';
                    $input['created_by'] = auth()->user()->id;
                    $input['updated_by'] = auth()->user()->id;
                    $input['created_at'] = Carbon::now();
                    $input['updated_at'] = Carbon::now();

//                    dd($input);
                    Asumsi_Umum::create($input);
                }
            });

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
//            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            //            dd($request);
            $request->validate([
                "id_periode" => 'required',
                "kurs" => 'required',
                "handling_bb" => 'required',
                "data_saldo_awal" => 'required',
            ]);

            $input['periode_id'] = $request->id_periode;
            $input['kurs'] = $request->kurs;
            $input['handling_bb'] = $request->handling_bb;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Asumsi_Umum::where('id', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            //            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {

            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            Asumsi_Umum::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
