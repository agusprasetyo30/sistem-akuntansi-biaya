<?php

namespace App\Http\Controllers;

use App\DataTables\Master\AsumsiUmumDataTable;
use App\Models\Asumsi_Umum;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsumsiUmumController extends Controller
{
    public function index(Request $request, AsumsiUmumDataTable $asumsiUmumDataTable)
    {
        if ($request->data == 'index') {
            return $asumsiUmumDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.master.asumsi_umum.index');
        }
        return view('pages.master.asumsi_umum.index');
    }

    public function create(Request $request)
    {
        try {
            $validasi = $request->all();

            foreach ($validasi['asumsi'] as $key => $items) {
                $validasi['asumsi'][$key]['kurs'] = str_replace(',00', '', str_replace('.', '', str_replace('Rp ', '', $items['kurs'])));
            }

            $validator = Validator::make($validasi, [
                "versi" => 'required',
                "jumlah_bulan" => 'required',
                "start_date" => 'required',
                "asumsi" => 'required',
                "asumsi.*.kurs" => 'required|min:0|not_in:0',
            ], validatorMsg())
                ->setAttributeNames(
                    ['asumsi.*.kurs' => 'kurs']
                );

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            DB::transaction(function () use ($request) {
                $lenght_data = count($request->asumsi);

                foreach ($request->asumsi as $key => $items) {
                    $saldo_awal = Carbon::createFromFormat('m/Y', $request->asumsi[$key]['peride_month'])->subMonth()->format('Y-m-01 00:00:00');
                    $month_year = Carbon::createFromFormat('m/Y', $items['peride_month'])->format('Y-m-01 00:00:00');

                    if ($key == 0) {
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
                    $input['usd_rate'] = (float) str_replace(',', '.', str_replace('.', '', str_replace('Rp ', '', $items['kurs'])));
                    $input['adjustment'] = (float) $items['adjustment'];
                    $input['inflasi'] = (float) $items['inflasi'];
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

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validasi = $request->all();

            foreach ($validasi['answer'] as $key => $items) {
                $validasi['answer'][$key]['kurs'] = str_replace(',00', '', str_replace('.', '', str_replace('Rp ', '', $items['kurs'])));
            }

            $validator = Validator::make($validasi, [
                "id" => 'required',
                "nama_version" => 'required',
                "jumlah_bulan" => 'required',
                "tanggal" => 'required',
                "answer" => 'required',
                "answer.*.kurs" => 'required|min:0|not_in:0',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            DB::transaction(function () use ($request) {
                $lenght_data = count($request->answer);


                foreach ($request->answer as $key => $items) {
                    $saldo_awal = Carbon::createFromFormat('Y-m-d', $items['periode'])->subMonth()->format('Y-m-01 00:00:00');
                    if ($key == 0) {
                        $input_version['version'] = $request->nama_version;
                        $input_version['data_bulan'] = $request->jumlah_bulan;
                        $input_version['awal_periode'] = Carbon::createFromFormat('Y-m-d', $request->answer[0]['periode'])->format('Y-m-01 00:00:00');
                        $input_version['akhir_periode'] = Carbon::createFromFormat('Y-m-d', $request->answer[$lenght_data - 1]['periode'])->format('Y-m-01 00:00:00');
                        $input_version['company_code'] = 'B000';

                        Asumsi_Umum::where('version_id', $request->id)
                            ->where('month_year', '<', $input_version['awal_periode'])
                            ->delete();

                        Version_Asumsi::where('id', $request->id)
                            ->update($input_version);
                    }

                    $input['version_id'] = $request->id;
                    $input['usd_rate'] = (float) str_replace(',', '.', str_replace('.', '', str_replace('Rp ', '', $items['kurs'])));
                    $input['adjustment'] = (float) $items['adjustment'];
                    $input['inflasi'] = (float) $items['inflasi'];
                    $input['month_year'] = Carbon::createFromFormat('Y-m-d', $items['periode'])->format('Y-m-01 00:00:00');
                    $input['saldo_awal'] = $saldo_awal;
                    $input['company_code'] = 'B000';
                    $input['created_by'] = auth()->user()->id;
                    $input['updated_by'] = auth()->user()->id;
                    //                    $input['updated_at'] = Carbon::now();

                    $cek_data = Asumsi_Umum::where('version_id', $request->id)
                        ->where('month_year', Carbon::createFromFormat('Y-m-d', $items['periode'])->format('Y-m-01 00:00:00'))
                        ->first();

                    if ($cek_data == null) {
                        $input['created_by'] = auth()->user()->id;
                        Asumsi_Umum::create($input);
                    } else {
                        Asumsi_Umum::where('version_id', $request->id)
                            ->where('month_year', Carbon::createFromFormat('Y-m-d', $items['periode'])->format('Y-m-01 00:00:00'))
                            ->update($input);
                    }
                }
            });

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {

            Version_Asumsi::where('id', $request->id)
                ->delete();
            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    // Helper
    public function view(Request $request)
    {
        try {
            $data['version'] = Version_Asumsi::where('id', $request->id)
                ->first();

            $data['asumsi'] = Asumsi_Umum::where('version_id', $request->id)
                ->get();

            return response()->json(['code' => 200, 'data' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 500, 'data' => '']);
        }
    }

    public function view_edit(Request $request)
    {

        try {
            $date = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-01 00:00:00');

            //            dd($request, $request->date ,$date);
            $data = Asumsi_Umum::where('version_id', $request->id)
                ->where('month_year', '=', $date)
                ->first();

            //            dd($data, $date);

            if ($data != null) {
                return response()->json(['code' => 200, 'data' => $data]);
            } else {
                return response()->json(['code' => 201, 'data' => '']);
            }
        } catch (\Exception $exception) {
            return response()->json(['code' => 500, 'data' => '']);
        }
    }
}
