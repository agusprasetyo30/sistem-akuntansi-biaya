<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_PriceRenDaanDataTable;
use App\DataTables\Master\PriceRenDaanDataTable;
use App\Exports\MultipleSheet\MS_KuantitiRenDaanExport;
use App\Exports\Horizontal\PriceRenDaanExport;
use App\Exports\MultipleSheet\MS_PriceRenDaanExport;
use App\Imports\KuantitiRenDaanImport;
use App\Imports\PriceRenDaanImport;
use App\Models\Asumsi_Umum;
use App\Models\Material;
use App\Models\PriceRenDaan;
use App\Models\QtyRenDaan;
use App\Models\Regions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PriceRenDaanController extends Controller
{
    public function index(Request $request, PriceRenDaanDataTable $pricerendaanDataTable, H_PriceRenDaanDataTable $h_PriceRenDaanDataTable)
    {
        if ($request->data == 'index') {
            if ($request->currency) {
                return $pricerendaanDataTable->with(['currency' => $request->currency, 'filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.price_rendaan.index');
            } else {
                return $pricerendaanDataTable->with(['currency' => 'Rupiah', 'filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.price_rendaan.index');
            }
        } elseif ($request->data == 'horizontal') {
            if ($request->currency) {
                return $h_PriceRenDaanDataTable->with(['company' => $request->company, 'version' => $request->version, 'currency' => $request->currency])->render('pages.buku_besar.price_rendaan.index');
            } else {
                return $h_PriceRenDaanDataTable->with(['company' => $request->company, 'version' => $request->version, 'currency' => 'Rupiah'])->render('pages.buku_besar.price_rendaan.index');
            }
        } elseif ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();
            return response()->json(['code' => 200, 'asumsi' => $asumsi]);
        }
        return view('pages.buku_besar.price_rendaan.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "mata_uang" => 'required',
                "price_rendaan_value" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }



            $check_data = PriceRenDaan::where([
                'company_code' => auth()->user()->company_code,
                'version_id' => $request->version_asumsi,
                'asumsi_umum_id' => $request->bulan,
                'region_name' => $request->region_id,
                'material_code' => $request->material_id
            ])->first();


            if ($request->mata_uang != 'IDR') {
                $check_kurs = Asumsi_Umum::where('id', $request->bulan)->first();
                $temp = (float) str_replace('.', '', str_replace(['Rp ', '$ '], '', $request->price_rendaan_value));
                $result = (float) $temp * (float) $check_kurs->usd_rate;
                $input['price_rendaan_value'] = $result;
            } else {
                $input['price_rendaan_value'] = (float) str_replace('.', '', str_replace(['Rp ', '$ '], '', $request->price_rendaan_value));
            }

            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_name'] = $request->region_id;
            $input['type_currency'] = $request->mata_uang;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            if ($check_data != null) {
                PriceRenDaan::where('id', $check_data->id)
                    ->update($input);
            } else {
                PriceRenDaan::create($input);
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
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "price_rendaan_value" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_name'] = $request->region_id;
            $input['price_rendaan_value'] = (float) str_replace('.', '', str_replace(['Rp ', '$ '], '', $request->price_rendaan_value));
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            PriceRenDaan::where('id', $request->id)
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
            PriceRenDaan::where('id', $request->id)
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

        return Excel::download(new MS_PriceRenDaanExport($version), 'price_rendaan.xlsx');
    }

    public function export_horizontal(Request $request)
    {
        $cc = auth()->user()->company_code;
        $mata_uang = $request->mata_uang;

        $query = DB::table('price_rendaan')
            ->select('price_rendaan.material_code', 'material.material_name', 'regions.region_desc', 'asumsi_umum.month_year', 'asumsi_umum.usd_rate', DB::raw('SUM(price_rendaan.price_rendaan_value) as price_rendaan_value'))
            ->leftjoin('material', 'material.material_code', '=', 'price_rendaan.material_code')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'price_rendaan.version_id')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'price_rendaan.asumsi_umum_id')
            ->leftjoin('regions', 'regions.region_name', '=', 'price_rendaan.region_name')
            ->whereNull('price_rendaan.deleted_at')
            ->where('price_rendaan.version_id', $request->version)
            // ->where('pj_pemakaian.company_code', $cc)
            ->groupByRaw("price_rendaan.material_code, material.material_name, material.material_uom, regions.region_desc, asumsi_umum.month_year, asumsi_umum.usd_rate")
            ->orderBy('material_code')
            ->orderBy('month_year')
            ->orderBy('region_desc')
            ->get()
            ->toArray();

        if ($mata_uang == 'USD') {
            $query = array_map(function ($val) {
                $val->price_rendaan_value = round($val->price_rendaan_value / $val->usd_rate, 2);
                return $val;
            }, $query);
        }

        $data = array_reduce($query, function ($acc, $curr) {
            if (property_exists($acc, $curr->material_code . ' - ' . $curr->material_name)) {
                if (property_exists($acc->{$curr->material_code . ' - ' . $curr->material_name}, $curr->region_desc)) {
                    array_push($acc->{$curr->material_code . ' - ' . $curr->material_name}->{$curr->region_desc}, $curr->price_rendaan_value);
                } else {
                    $acc->{$curr->material_code . ' - ' . $curr->material_name}->{$curr->region_desc} = [$curr->price_rendaan_value];
                }
            } else {
                $acc->{$curr->material_code . ' - ' . $curr->material_name} = (object)[$curr->region_desc => [$curr->price_rendaan_value]];
            }

            return $acc;
        }, (object)[]);



        $header = ['MATERIAL', 'REGION', ...array_unique(array_map(function ($v) {
            return $v->month_year;
        }, $query))];


        $body = array_reduce(array_map(function ($v) use ($data) {
            $_arrays = [];

            foreach ($data->{$v} as $w => $x) {
                $_arr = [$v, $w];

                foreach ($x as $y) {
                    array_push($_arr, $y);
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
            'body'   => $body,
            'mata_uang' => $mata_uang
        ];

        return Excel::download(new PriceRenDaanExport($data), "Price Rencana Pengadaan - Horizontal.xlsx");
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                "version" => 'required',
                "mata_uang" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $transaction = DB::transaction(function () use ($request) {
                $data_kurs = Asumsi_Umum::where('version_id', $request->version)->get('usd_rate')->toArray();
                $empty_excel = Excel::toArray(new PriceRenDaanImport($request->version, $request->mata_uang, $data_kurs), $request->file('file'));
                if ($empty_excel[0]) {
                    $file = $request->file('file')->store('import');

                    PriceRenDaan::where('version_id', $request->version)->delete();
                    $import = new PriceRenDaanImport($request->version, $request->mata_uang, $data_kurs);
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
            $data_kurs = Asumsi_Umum::where('version_id', $request->version)->get('usd_rate')->toArray();
            $empty_excel = Excel::toArray(new PriceRenDaanImport($request->version, $request->mata_uang, $data_kurs), $request->file('file'));

            $material = [];
            $material_ = [];
            $region = [];
            $region_ = [];

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
                ]);
            }
        }
    }

    public function check(Request $request)
    {
        try {
            $check = PriceRenDaan::where('version_id', $request->version)
                ->first();
            if ($check == null) {
                return response()->json(['code' => 200, 'msg' => 'Data Tidak Ada']);
            } else {
                return response()->json(['code' => 201, 'msg' => 'Data Ada']);
            }
        } catch (\Exception $exception) {
            return response()->json(['code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
