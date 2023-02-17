<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_SalrDataTable;
use App\DataTables\Master\SalrDataTable;
use App\Exports\MultipleSheet\MS_SalrExport;
use App\Imports\SalrImport;
use App\Models\CostCenter;
use App\Models\GLAccountFC;
use App\Models\Material;
use App\Models\Salr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Horizontal\H_Salr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SalrController extends Controller
{
    public function index(Request $request, SalrDataTable $salrDataTable, H_SalrDataTable $h_SalrDataTable)
    {
        if ($request->data == 'index') {
            return $salrDataTable->render('pages.buku_besar.salr.index');
        } elseif ($request->data == 'horizontal') {
            return $h_SalrDataTable->with([
                'format' => $request->format_data,
                'cost_center' => $request->cost_center,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'moth' => $request->moth,
                'year' => $request->year,
                'inflasi' => $request->inflasi,
                'inflasi_asumsi' => $request->inflasi_asumsi,
            ])->render('pages.buku_besar.salr.index');
        } elseif ($request->data == 'dynamic') {
            $cost_center = Salr::select('salrs.cost_center', 'cost_center.cost_center_desc')
                ->leftjoin('cost_center', 'salrs.cost_center', '=', 'cost_center.cost_center')
                ->groupBy('salrs.cost_center', 'cost_center.cost_center_desc');

            // Periode
            if ($request->format_data == '0') {
                $cost_center->where('salrs.periode', 'ilike', '%' . $request->year . '%');
            } elseif ($request->format_data == '1') {
                $temp = explode('-', $request->moth);
                $timemonth = $temp[1] . '-' . $temp[0];

                $cost_center->where('salrs.periode', 'ilike', '%' . $timemonth . '%');
            } elseif ($request->format_data == '2') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $cost_center->whereBetween('salrs.periode', [$start_date, $end_date]);
            }

            if ($request->cost_center != 'all') {
                $cost_center->where('salrs.cost_center', $request->cost_center);
            }

            $cost_center = $cost_center->get();

            return response()->json(['code' => 200, 'cost_center' => $cost_center]);
        }
        return view('pages.buku_besar.salr.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "ga_account" => 'required',
                "gl_account" => 'required',
                "cost_center" => 'required',
                "tanggal" => 'required',
                "value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $timestamps = explode('-', $request->tanggal);

            $input['gl_account_fc'] = $request->gl_account;
            $input['cost_center'] = $request->cost_center;
            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_id;
            $input['periode'] = $timestamps[1] . '-' . $timestamps[0] . '-01';
            $input['value'] = (float) str_replace('.', '', str_replace('Rp ', '', $request->value));
            $input['name'] = $request->nama;
            $input['partner_cost_center'] = $request->partner_cost_center;
            $input['username'] = $request->username;
            $input['material_code'] = $request->material;
            $input['document_number'] = $request->document_num;
            $input['document_number_text'] = $request->document_num_desc;
            $input['purchase_order'] = $request->purchase_order;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            Salr::create($input);

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
                "ga_account" => 'required',
                "gl_account" => 'required',
                "cost_center" => 'required',
                "tanggal" => 'required',
                "value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $timestamps = explode('-', $request->tanggal);

            $input['gl_account_fc'] = $request->gl_account;
            $input['cost_center'] = $request->cost_center;
            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_id;
            $input['periode'] = $timestamps[1] . '-' . $timestamps[0] . '-01';
            $input['value'] = (float) str_replace('.', '', str_replace('Rp ', '', $request->value));
            $input['name'] = $request->nama;
            $input['partner_cost_center'] = $request->partner_cost_center;
            $input['username'] = $request->username;
            $input['material_code'] = $request->material;
            $input['document_number'] = $request->document_num;
            $input['document_number_text'] = $request->document_num_desc;
            $input['purchase_order'] = $request->purchase_order;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            Salr::where('id', $request->id)
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

            Salr::where('id', $request->id)
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

    public function export(Request $request)
    {
        return Excel::download(new MS_SalrExport(), 'SALR.xlsx');
    }

    public function export_horizon_salr(Request $request)
    {
        // dd('bagas');
        return Excel::download(new H_Salr(), 'SALR_HORIZONTAL.xlsx');
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                'tanggal_import' => 'required'
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $transaction = DB::transaction(function () use ($request) {
                $temp = explode('-', $request->tanggal_import);
                $timestamp = $temp[1] . '-' . $temp[0] . '-01';
                $empty_excel = Excel::toArray(new SalrImport($timestamp), $request->file('file'));
                if ($empty_excel[0]) {
                    $file = $request->file('file')->store('import');


                    Salr::where('periode', 'ilike', '%' . $timestamp . '%')->delete();
                    $import = new SalrImport($timestamp);
                    $import->import($file);

                    $data_fail = $import->failures();
                } else {
                    $data_fail = [];
                }
                return $data_fail;
            });

            if ($transaction->isNotEmpty()) {
                return setResponse([
                    'code' => 500,
                    'title' => 'Gagal meng-import data',
                ]);
            } else {
                return setResponse([
                    'code' => 200,
                    'title' => 'Berhasil meng-import data'
                ]);
            }
        } catch (\Exception $exception) {
            $temp = explode('-', $request->tanggal_import);
            $timestamp = $temp[1] . '-' . $temp[0] . '-01';
            $empty_excel = Excel::toArray(new SalrImport($timestamp), $request->file('file'));

            $gl_account = [];
            $gl_account_ = [];
            $cost_center = [];
            $cost_center_ = [];
            $material = [];
            $material_ = [];

            foreach ($empty_excel[0] as $key => $value) {
                array_push($gl_account, 'gl account ' . $value['gl_account_fc'] . ' tidak ada pada master');
                $d_gl_account = GLAccountFC::whereIn('gl_account_fc', [$value['gl_account_fc']])->first();
                if ($d_gl_account) {
                    array_push($gl_account_, 'gl account ' . $d_gl_account->gl_account_fc . ' tidak ada pada master');
                }

                array_push($cost_center, 'cost center ' . $value['cost_center'] . ' tidak ada pada master');
                $d_cost_center = CostCenter::whereIn('cost_center', [$value['cost_center']])->first();
                if ($d_cost_center) {
                    array_push($cost_center_, 'cost center ' . $d_cost_center->cost_center . ' tidak ada pada master');
                }

                array_push($material, 'material ' . $value['material_code'] . ' tidak ada pada master');
                $d_material = Material::whereIn('material_code', [$value['material_code']])->first();
                if ($d_material) {
                    array_push($material_, 'material ' . $d_material->material_code . ' tidak ada pada master');
                }
            }

            $result_gl_account = array_diff($gl_account, $gl_account_);
            $result_cost_center = array_diff($cost_center, $cost_center_);
            $result_material = array_diff($material, $material_);
            $result = array_merge($result_gl_account, $result_cost_center, $result_material);
            $res = array_unique($result);

            if ($res) {
                $msg = '';

                foreach ($res as $message)
                    $msg .= '<p>' . $message . '</p>';

                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => $msg
                ]);
            } else {
                return setResponse([
                    'code' => 400,
                ]);
            }
        }
    }

    public function check(Request $request)
    {
        try {
            $timestamp = explode('-', $request->periode);
            $check = Salr::where('periode', 'ilike', '%' . $timestamp[1] . '-' . $timestamp[0] . '-01' . '%')
                ->first();
            if ($check == null) {
                return response()->json(['code' => 200, 'msg' => 'Data Tidak Ada']);
            } else {
                return response()->json(['code' => 201, 'msg' => 'Data Ada']);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
