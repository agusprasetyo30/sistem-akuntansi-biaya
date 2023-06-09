<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_QtyRenDaanDataTable;
use App\DataTables\Master\QtyRenDaanDataTable;
use App\Exports\MultipleSheet\MS_KuantitiRenDaanExport;
use App\Exports\Horizontal\QtyRenDaanExport;
use App\Exports\Template\T_KuantitiRenDaanExport;
use App\Imports\KuantitiRenDaanImport;
use App\Models\Asumsi_Umum;
use App\Models\Material;
use App\Models\QtyRenDaan;
use App\Models\Regions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class QtyRenDaanController extends Controller
{
    public function index(Request $request, QtyRenDaanDataTable $qtyrendaanDataTable, H_QtyRenDaanDataTable $h_QtyRenDaanDataTable)
    {
        if ($request->data == 'index') {
            return $qtyrendaanDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.qty_rendaan.index');
        } elseif ($request->data == 'horizontal') {
            return $h_QtyRenDaanDataTable->with(['version' => $request->version, 'company' => $request->company])->render('pages.buku_besar.qty_rendaan.index');
        } elseif ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();
            return response()->json(['code' => 200, 'asumsi' => $asumsi]);
        }
        return view('pages.buku_besar.qty_rendaan.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "qty_rendaan_value" => 'required|numeric',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $check_data = QtyRenDaan::where([
                'company_code' => auth()->user()->company_code,
                'version_id' => $request->version_asumsi,
                'asumsi_umum_id' => $request->bulan,
                'region_name' => $request->region_id,
                'material_code' => $request->material_id
            ])->first();


            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_name'] = $request->region_id;
            $input['qty_rendaan_value'] = (float) $request->qty_rendaan_value;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            if ($check_data != null) {
                QtyRenDaan::where('id', $check_data->id)
                    ->update($input);
            } else {
                QtyRenDaan::create($input);
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
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "qty_rendaan_value" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_name'] = $request->region_id;
            $input['qty_rendaan_value'] = (float) $request->qty_rendaan_value;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            QtyRenDaan::where('id', $request->id)
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
            QtyRenDaan::where('id', $request->id)
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
        $version = $request->temp;

        return Excel::download(new MS_KuantitiRenDaanExport($version), 'qty_rendaan.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }

    public function export_horizontal(Request $request)
    {
        // $cc = auth()->user()->company_code;

        $month_year = array_map(function($val) {
            return $val->month_year;
        }, DB::table(('asumsi_umum'))->select('month_year')
            ->where("asumsi_umum.version_id", $request->version)
            ->orderBy('month_year')
            ->get()
            ->toArray());

        $query = DB::table('qty_rendaan')->select('qty_rendaan.material_code', 'material.material_name', 'material.material_uom', 'regions.region_desc', 'asumsi_umum.month_year', DB::raw('SUM(qty_rendaan.qty_rendaan_value) as qty_rendaan_value'))
            ->leftjoin('material', 'qty_rendaan.material_code', '=', 'material.material_code')
            ->leftjoin('asumsi_umum', 'qty_rendaan.asumsi_umum_id', '=', 'asumsi_umum.id')
            ->leftjoin('version_asumsi', 'qty_rendaan.version_id', '=', 'version_asumsi.id')
            ->leftjoin('regions', 'qty_rendaan.region_name', '=', 'regions.region_name')
            ->whereNull('qty_rendaan.deleted_at')
            ->where('qty_rendaan.version_id', $request->version)
            // ->where('qty_rendaan.company_code', $cc)
            ->groupByRaw("qty_rendaan.material_code, material.material_name, material.material_uom, regions.region_desc, asumsi_umum.month_year")
            ->orderBy('material_code')
            ->orderBy('month_year')
            ->orderBy('region_desc')
            ->get()
            ->toArray();


        $data = array_reduce($query, function ($acc, $curr) {
            if (property_exists($acc, $curr->material_code . ' - ' . $curr->material_name . '~' . $curr->material_uom)) {
                if (!property_exists($acc->{$curr->material_code . ' - ' . $curr->material_name . '~' . $curr->material_uom}, $curr->region_desc)) {
                    $acc->{$curr->material_code . ' - ' . $curr->material_name . '~' . $curr->material_uom}->{$curr->region_desc} = (object)[];
                }

                $acc->{$curr->material_code . ' - ' . $curr->material_name . '~' . $curr->material_uom}->{$curr->region_desc}->{$curr->month_year} = $curr->qty_rendaan_value;

            } else {
                $acc->{$curr->material_code . ' - ' . $curr->material_name . '~' . $curr->material_uom} = (object)[];
                $acc->{$curr->material_code . ' - ' . $curr->material_name . '~' . $curr->material_uom}->{$curr->region_desc} = (object)[];
                $acc->{$curr->material_code . ' - ' . $curr->material_name . '~' . $curr->material_uom}->{$curr->region_desc}->{$curr->month_year} = $curr->qty_rendaan_value;
            }

            return $acc;
        }, (object)[]);


        $header = ['MATERIAL', 'REGION', 'UOM', ...$month_year];


        $body = array_reduce(array_map(function ($v) use ($data, $month_year) {
            list($mat, $uom) = explode("~", $v);
            $_arrays = [];

            foreach ($data->{$v} as $w => $x) {
                $_arr = [$mat, $w, $uom];

                foreach($month_year as $e) {
                    $val = -1;
                    if (property_exists($x, $e)) {
                        $val = $x->{$e};
                    }
                    array_push($_arr, $val);
                }

                array_push($_arrays, $_arr);
            }
            return $_arrays;
        }, array_keys(get_object_vars($data))), function ($acc, $curr) {
            return array_merge($acc, $curr);
        }, []);

        // return response()->json($body);


        $data = [
            'header' => $header,
            'body'   => $body
        ];

        return Excel::download(new QtyRenDaanExport($data), "Qty Ren Daan - Horizontal.xlsx");
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                "version" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            DB::transaction(function () use ($request) {
                QtyRenDaan::where('version_id', $request->version)->delete();

                $file = $request->file('file')->store('import');

                $import = new KuantitiRenDaanImport($request->version);
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
//            dd($exception);
            $empty_excel = Excel::toArray(new KuantitiRenDaanImport($request->version), $request->file('file'));

            $material = [];
            $material_ = [];
            $region = [];
            $region_ = [];
            //            dd($empty_excel[0]);
            foreach ($empty_excel[0] as $key => $value) {
                array_push($material, 'Material ' . $value['material_code'] . ' tidak ada pada master');
                $d_material = Material::whereIn('material_code', [$value['material_code']])->first();
                if ($d_material) {
                    array_push($material_, 'Material ' . $d_material->material_code . ' tidak ada pada master');
                }

                array_push($region, 'Region ' . $value['region_name'] . ' tidak ada pada master');
                $d_region = Regions::whereIn('region_name', [$value['region_name']])->first();
                if ($d_region) {
                    array_push($region_, 'Region ' . $d_region->region_name . ' tidak ada pada master');
                }
            }

            $result_material = array_diff($material, $material_);
            $result_region = array_diff($region, $region_);
            $result = array_merge($result_region, $result_material);
            $res = array_unique($result);

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

    public function check(Request $request)
    {
        try {
            $check = QtyRenDaan::where('version_id', $request->version)
                ->first();
            if ($check == null) {
                return response()->json(['Code' => 200, 'msg' => 'Data Tidak Ada']);
            } else {
                return response()->json(['Code' => 201, 'msg' => 'Data Ada']);
            }
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
