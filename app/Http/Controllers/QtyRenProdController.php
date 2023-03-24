<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_QtyRenProdDataTable;
use App\DataTables\Master\QtyRenProdDataTable;
use App\Exports\MultipleSheet\MS_KuantitiRenProdExport;
use App\Imports\QtyRenProdImport;
use App\Models\Asumsi_Umum;
use App\Models\CostCenter;
use App\Models\QtyRenProd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class QtyRenProdController extends Controller
{
    public function index(Request $request, QtyRenProdDataTable $qtyrenprodDataTable, H_QtyRenProdDataTable $h_QtyRenProdDataTable)
    {
        if ($request->data == 'index') {
            return $qtyrenprodDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.qty_renprod.index');
        } elseif ($request->data == 'horizontal') {
            return $h_QtyRenProdDataTable->with(['version' => $request->version])->render('pages.buku_besar.qty_renprod.index');
        } elseif ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();
            return response()->json(['code' => 200, 'asumsi' => $asumsi]);
        }
        return view('pages.buku_besar.qty_renprod.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "cost_center" => 'required',
                "qty_renprod_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            // $qty_renprod_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->qty_renprod_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['cost_center'] = $request->cost_center;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['qty_renprod_value'] = $request->qty_renprod_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            $data_renprod = QtyRenProd::where([
                'company_code' => auth()->user()->company_code,
                'cost_center' => $request->cost_center,
                'version_id' => (int) $request->version_id,
                'asumsi_umum_id' => $request->month_year,
            ])->first();

            if (!$data_renprod) {
                QtyRenProd::create($input);
            } else {
                QtyRenProd::where('id', $data_renprod->id)->update($input);
            }

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

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "cost_center" => 'required',
                "qty_renprod_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            // $qty_renprod_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->qty_renprod_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['cost_center'] = $request->cost_center;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['qty_renprod_value'] = $request->qty_renprod_value;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            QtyRenProd::where('id', $request->id)
                ->update($input);

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

    public function delete(Request $request)
    {
        try {
            QtyRenProd::where('id', $request->id)
                ->delete();

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            DB::transaction(function () use ($request) {
                QtyRenProd::where('version_id', $request->version)->delete();
                $file = $request->file('file')->store('import');
                $import = new QtyRenProdImport($request->version);
                $import->import($file);

                $data_fail = $import->failures();

                if ($data_fail->isNotEmpty()) {
                    return setResponse([
                        'code' => 500,
                        'title' => 'Gagal meng-import data',
                    ]);
                }
            });

            return setResponse([
                'code' => 200,
                'title' => 'Berhasil meng-import data'
            ]);
        } catch (\Exception $exception) {
            if ($exception->getCode() == 23503) {
                $empty_excel = Excel::toArray(new QtyRenProdImport($request->version), $request->file('file'));
                $cost_center = [];
                $cost_center_ = [];
                foreach ($empty_excel[0] as $key => $value) {
                    array_push($cost_center, 'cost center ' . $value['cost_center'] . ' tidak ada pada master');
                    $d_cost_center = CostCenter::whereIn('cost_center', [$value['cost_center']])->first();
                    if ($d_cost_center) {
                        array_push($cost_center_, 'cost center ' . $d_cost_center->cost_center . ' tidak ada pada master');
                    }
                }

                $result_cost_center = array_diff($cost_center, $cost_center_);
                $res = array_unique($result_cost_center);

                if ($res) {
                    $msg = '';

                    foreach ($res as $message)
                        $msg .= '<p>' . $message . '</p>';

                    return setResponse([
                        'code' => 430,
                        'title' => 'Gagal meng-import data',
                        'message' => $msg
                    ]);
                }
            } elseif ($exception->getCode() == 0) {
                //                dd($exception->getMessage());
                return setResponse([
                    'code' => 431,
                    'title' => 'Gagal meng-import data',
                    'message' => 'Format Tidak Sesuai.'
                ]);
            } else {
                return setResponse([
                    'code' => 400,
                ]);
            }
        }
    }

    public function export(Request $request)
    {
        if (!$request->version) {
            return setResponse([
                'code' => 500,
            ]);
        }
        $version = $request->version;

        return Excel::download(new MS_KuantitiRenProdExport($version), 'qty_renprod.xlsx');
    }

    public function check(Request $request)
    {
        try {
            $check = QtyRenProd::where('version_id', $request->version)
                ->first();
            if ($check == null) {
                return setResponse([
                    'code' => 200,
                ]);
            } else {
                return setResponse([
                    'code' => 201,
                    'title' => 'Apakah anda yakin?',
                    'message' => 'Data Pada Versi Ini Telah Ada, Yakin Untuk Mengganti ?'
                ]);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
