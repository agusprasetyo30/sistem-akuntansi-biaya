<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_PJPenjualanDataTable;
use App\DataTables\Master\PJPenjualanDataTable;
use App\Exports\MultipleSheet\MS_PJPenjualanExport;
use App\Exports\Horizontal\PJPenjualanExport;
use App\Exports\Vertical\Vertikal_PJPenjualanExport;
use App\Imports\PJPenjualanImport;
use App\Models\Material;
use App\Models\PJ_Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PJPenjualanController extends Controller
{
    public function index(Request $request, PJPenjualanDataTable $penjualanDataTable, H_PJPenjualanDataTable $h_penjualanDataTable)
    {
        if ($request->data == 'index') {
            return $penjualanDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.pakai_jual.penjualan.index');
        } elseif ($request->data == 'horizontal') {
            return $h_penjualanDataTable->with(['version' => $request->version, 'company' => $request->company])->render('pages.buku_besar.pakai_jual.penjualan.index');
        } elseif ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();
            return response()->json(['code' => 200, 'asumsi' => $asumsi]);
        }
        return view('pages.buku_besar.pakai_jual.penjualan.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "material_code" => 'required',
                "pj_penjualan_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            // $pj_penjualan_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->pj_penjualan_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['pj_penjualan_value'] = $request->pj_penjualan_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            $data_renprod = PJ_Penjualan::where([
                'company_code' => auth()->user()->company_code,
                'material_code' => $request->material_code,
                'version_id' => (int) $request->version_id,
                'asumsi_umum_id' => $request->month_year,
            ])->first();

            if (!$data_renprod) {
                PJ_Penjualan::create($input);
            } else {
                PJ_Penjualan::where('id', $data_renprod->id)->update($input);
            }

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
            $validator = Validator::make($request->all(), [
                "material_code" => 'required',
                "pj_penjualan_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            // $pj_penjualan_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->pj_penjualan_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['pj_penjualan_value'] = $request->pj_penjualan_value;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            PJ_Penjualan::where('id', $request->id)
                ->update($input);

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
            PJ_Penjualan::where('id', $request->id)
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

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            DB::transaction(function () use ($request) {
                PJ_Penjualan::where('version_id', $request->version)->delete();
                $file = $request->file('file')->store('import');
                $import = new PJPenjualanImport($request->version);
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
            $empty_excel = Excel::toArray(new PJPenjualanImport($request->version), $request->file('file'));
            $material_code = [];
            $material_code_ = [];
            foreach ($empty_excel[0] as $key => $value) {
                array_push($material_code, 'material code ' . $value['material_code'] . ' tidak ada pada master');
                $d_material_code = Material::whereIn('material_code', [$value['material_code']])->first();
                if ($d_material_code) {
                    array_push($material_code_, 'material code ' . $d_material_code->material_code . ' tidak ada pada master');
                }
            }

            $result_material_code = array_diff($material_code, $material_code_);
            $res = array_unique($result_material_code);

            if ($res) {
                $msg = '';

                foreach ($res as $message) {
                    $msg .= '<p>' . $message . '</p>';
                }

                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => $msg
                ]);
            } else {
                return setResponse([
                    'code' => 400,
                    'title' => $exception->getMessage()
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

        return Excel::download(new MS_PJPenjualanExport($version), 'penjualan.xlsx');
    }

    public function export_horizontal(Request $request)
    {
        $cc = auth()->user()->company_code;

        $month_year = array_map(function($val) {
            return $val->month_year;
        }, DB::table(('asumsi_umum'))->select('month_year')
            ->where("asumsi_umum.version_id", $request->version)
            ->orderBy('month_year')
            ->get()
            ->toArray());

        $query = DB::table('pj_penjualan')
            ->select(DB::Raw("CONCAT(pj_penjualan.material_code, ' ', material.material_name) mat"), 'material.material_uom', 'asumsi_umum.month_year', DB::Raw('COALESCE(pj_penjualan.pj_penjualan_value, -1) pj_penjualan_value'))
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'pj_penjualan.asumsi_umum_id')
            ->leftjoin('material', 'material.material_code', '=', 'pj_penjualan.material_code')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'pj_penjualan.version_id')
            ->whereNull('pj_penjualan.deleted_at')
            ->where('pj_penjualan.version_id', $request->version)
            // ->where('pj_penjualan.company_code', $cc)
            ->orderBy('mat')
            ->orderBy('month_year')
            ->get()
            ->toArray();


        $data = array_reduce($query, function ($carry, $item) {
            if (!property_exists($carry, $item->mat . '~' . $item->material_uom)) {
                $carry->{$item->mat . '~' . $item->material_uom} = (object)[];
            }

            $carry->{$item->mat . '~' . $item->material_uom}->{$item->month_year} = $item->pj_penjualan_value;

            return $carry;
        }, (object)[]);


        $header = ['MATERIAL', 'UOM', ...$month_year];


        $body = array_map(function ($v) use ($data, $month_year) {
            list($mat, $uom) = explode("~", $v);
            $_arr = [$mat, $uom];

            foreach($month_year as $e) {
                $val = -1;
                if (property_exists($data->{$v}, $e)) {
                    $val = $data->{$v}->{$e};
                }
                array_push($_arr, $val);
            }

            return $_arr;
        }, array_keys(get_object_vars($data)));


        $data = [
            'header' => $header,
            'body'   => $body
        ];

        return Excel::download(new PJPenjualanExport($data), "PJ Penjualan - Horizontal.xlsx");
    }

    public function export_vertikal(Request $request)
    {
        $penjualan = DB::table('pj_penjualan')
            ->select('pj_penjualan.*', 'asumsi_umum.month_year', 'version_asumsi.version', DB::Raw("CONCAT(material.material_code, ' ', material.material_name) materialjoin"))
            ->leftjoin('material', 'material.material_code', '=', 'pj_penjualan.material_code')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'pj_penjualan.version_id')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'pj_penjualan.asumsi_umum_id')
            ->whereNull('pj_penjualan.deleted_at')
            ->orderBy('materialjoin', 'asc');
            // ->orderBy('time DESC');

        if($request->version2 != 'all') {
            $penjualan->where('a.version_id', $request->version2);
        }

        try {
            $penjualan = $penjualan
                    ->get()
                    ->toArray();
        } catch (\Throwable $th) {
            $penjualan = [];
        }
        // dd($penjualan);
        // return response()->json($labarugi);

        $data = [
            'penjualan' => $penjualan
        ];
        // dd($penjualan);

        return Excel::download(new Vertikal_PJPenjualanExport($data), "penjualan Vertikal.xlsx");
    }

    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // "file" => 'required',
            "version" => 'required',
        ], validatorMsg());

        if ($validator->fails()) {
            return $this->makeValidMsg($validator);
        }

        try {
            $check = PJ_Penjualan::where('version_id', $request->version)
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
                'title' => $exception->getMessage()
            ]);
        }
    }
}
