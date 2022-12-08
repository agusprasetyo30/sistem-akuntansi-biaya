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
            dd($request);

            DB::transaction(function () use ($request){
                $input_versi['version'] = $request->versi;
                $input_versi['data_bulan'] = $request->jumlah_bulan;

                $versi = Version_Asumsi::create($input_versi);

                foreach ($request->asumsi as $key=>$items){
                    $value = true;
                    $date = explode('/', $items['peride_month']);

                    $cek_saldo_awal = Carbon::createFromFormat('m/Y', $request->asumsi[0]['peride_month'])->format('Y-m-01 H:i:s');


                    if ($key == 0){
                        $cek_data_saldo_awal = DB::table('asumsi_umum')->where('saldo_awal', '<', $cek_saldo_awal)->orderByDesc('id')->first();
                        if ($cek_data_saldo_awal != null){
                            $saldo_awal = $cek_data_saldo_awal->saldo_awal;
                            $value = false;
                        }else{
                            $saldo_awal = $cek_saldo_awal;
                        }

                        if (count($request->asumsi) == 1){
                            $awal_periode = $cek_saldo_awal;
                            $akhir_periode = $cek_saldo_awal;
                        }else{
                            $awal_periode = $cek_saldo_awal;
                            $akhir_periode = Carbon::createFromFormat('m/Y', $request->asumsi[count($request->asumsi) - 1]['peride_month'])->format('Y-m-01 H:i:s');
                        }

                    }else{
                        if ($value){
                            $saldo_awal = Carbon::createFromFormat('m/Y', $request->asumsi[$key-1]['peride_month'])->format('Y-m-01 H:i:s');
                        }else{
                            $saldo_awal = Carbon::createFromFormat('m/Y', $request->asumsi[0]['peride_month'])->format('Y-m-01 H:i:s');
                        }
                    }

                    $input['version_id'] = $versi->id;
                    $input['usd_rate'] = (double) str_replace(',','.',str_replace('.','',str_replace('Rp ', '', $items['kurs'])));
                    $input['ajustment'] = (double) $items['ajustment'];
                    $input['month'] = check_month(((int) $date[0]) - 1);
                    $input['year'] = $date[1];
                    $input['saldo_awal'] = $saldo_awal;
                    $input['awal_periode'] = $awal_periode;
                    $input['akhir_periode'] = $akhir_periode;
                    $input['created_by'] = auth()->user()->id;
                    $input['updated_by'] = auth()->user()->id;
                    $input['created_at'] = Carbon::now();
                    $input['updated_at'] = Carbon::now();

                    Asumsi_Umum::create($input);
                }
            });

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            dd($exception);
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
