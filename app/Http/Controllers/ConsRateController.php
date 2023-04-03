<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ConsRateDataTable;
use App\Exports\MultipleSheet\MS_ComsumptionRatioExport;
use App\Exports\Template\T_ConsRateExport;
use App\Imports\ConsRateImport;
use App\Jobs\ConsRatePodcast;
use App\Models\Asumsi_Umum;
use App\Models\ConsRate;
use App\Models\Material;
use App\Models\Plant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use function PHPUnit\Framework\isEmpty;

class ConsRateController extends Controller
{
    public function index(Request $request, ConsRateDataTable $consRateDataTable)
    {

        $cons_rate = ConsRate::first();

        if ($request->data == 'index') {
            return $consRateDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.consrate.index');
        }

        if ($request->filter_version) {
            // $cons_rate = ConsRate::where('version_id', $request->filter_version)->first();
            $cons_rate = ConsRate::where('version_id', 3)->first();
        }

        return view('pages.buku_besar.consrate.index', compact('cons_rate'));
    }

    public function create(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                "id_plant" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
                "produk" => 'required',
                "material" => 'required',
                "consrate" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)
                ->first();

            $input['plant_code'] = $request->id_plant;
            $input['version_id'] = (int) $request->version;
            $input['product_code'] = $request->produk;
            $input['material_code'] = $request->material;
            $input['cons_rate'] = (float) $request->consrate;
            $input['month_year'] = $data_asumsi->month_year;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = 'B000';
            $input['status_pengajuan'] = 'DRAFT';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();


            $data_cek = ConsRate::where([
                'plant_code' => $request->id_plant,
                'version_id' => (int) $request->version,
                'product_code' => $request->produk,
                'material_code' => $request->material,
                'month_year' => $data_asumsi->month_year,
                'company_code' => 'B000'
            ])->first();

            if ($data_cek == null) {
                ConsRate::create($input);
            } else {
                ConsRate::where('id', $data_cek->id)->update($input);
            }
            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            //            dd($exception);
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "id_plant" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
                "produk" => 'required',
                "material" => 'required',
                "consrate" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            if (strpos($request->id_asumsi, '-') == true) {
                $data_asumsi = Asumsi_Umum::where([
                    'month_year' => $request->id_asumsi,
                    'version_id' => (int) $request->version
                ])
                    ->first();
            } else {
                $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)
                    ->first();
            }

            $input['plant_code'] = $request->id_plant;
            $input['version_id'] = (int) $request->version;
            $input['product_code'] = $request->produk;
            $input['material_code'] = $request->material;
            $input['cons_rate'] = (float) $request->consrate;
            $input['month_year'] = $data_asumsi->month_year;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            $data_cek = ConsRate::where([
                'plant_code' => $request->id_plant,
                'version_id' => (int) $request->version,
                'product_code' => $request->produk,
                'material_code' => $request->material,
                'month_year' => $data_asumsi->month_year,
                'company_code' => 'B000'
            ])->where('id', '!=', $request->id)->first();

            DB::transaction(function () use ($data_cek, $request, $input) {
                if ($data_cek == null) {
                    ConsRate::where('id', $request->id)->update($input);
                } else {
                    $temp = $data_cek->where('id', '!=', $request->id)->pluck('id')->all();
                    ConsRate::whereIn('id', $temp)->delete();
                    ConsRate::where('id', $request->id)->update($input);
                }
            });

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
            ConsRate::where('id', $request->id)
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
        return Excel::download(new MS_ComsumptionRatioExport(), 'cons_rate.xlsx');
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                "version" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            DB::transaction(function () use ($request) {
                ConsRate::where('version_id', $request->version)->delete();

                $file = $request->file('file')->store('import');

                $import = new ConsRateImport($request->version);
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
            dd($exception);
            $empty_excel = Excel::toArray(new ConsRateImport($request->version), $request->file('file'));

            $plant_code = [];
            $plant_code_ = [];
            $product_code = [];
            $product_code_ = [];
            $material = [];
            $material_ = [];


            foreach ($empty_excel[0] as $key => $value) {
                array_push($plant_code, 'Plant ' . $value['plant_code'] . ' tidak ada pada master');
                $d_plant_code = Plant::whereIn('plant_code', [$value['plant_code']])->first();
                if ($d_plant_code) {
                    array_push($plant_code_, 'Plant ' . $d_plant_code->plant_code . ' tidak ada pada master');
                }

                array_push($product_code, 'Product ' . $value['product_code'] . ' tidak ada pada master');
                $d_product = Material::whereIn('material_code', [$value['product_code']])->first();
                if ($d_product) {
                    array_push($product_code_, 'Product ' . $d_product->material_code . ' tidak ada pada master');
                }

                array_push($material, 'Material ' . $value['material_code'] . ' tidak ada pada master');
                $d_material = Material::whereIn('material_code', [$value['material_code']])->first();
                if ($d_material) {
                    array_push($material_, 'Material ' . $d_material->material_code . ' tidak ada pada master');
                }
            }

            $result_plant = array_diff($plant_code, $plant_code_);
            $result_product = array_diff($product_code, $product_code_);
            $result_material = array_diff($material, $material_);
            $result = array_merge($result_plant, $result_product, $result_material);
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
            $check = ConsRate::where('version_id', $request->version)
                ->first();
            if ($check == null) {
                return response()->json(['Code' => 200, 'msg' => 'Data ']);
            } else {
                return response()->json(['Code' => 201, 'msg' => 'Data Berasil Disimpan']);
            }
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function check_duplicated(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                "id_plant" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
                "produk" => 'required',
                "material" => 'required',
                "consrate" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            if (strpos($request->id_asumsi, '-') == true) {
                $data_asumsi = Asumsi_Umum::where([
                    'month_year' => $request->id_asumsi,
                    'version_id' => (int) $request->version
                ])
                    ->first();
            } else {
                $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)
                    ->first();
            }

            $data_cek = ConsRate::where([
                'plant_code' => $request->id_plant,
                'version_id' => (int) $request->version,
                'product_code' => $request->produk,
                'material_code' => $request->material,
                'month_year' => $data_asumsi->month_year,
                'company_code' => 'B000'
            ])->where('id', '!=', $request->id)
                ->first();

            if ($data_cek == null) {
                return response()->json(['code' => 200, 'msg' => 'Data ']);
            } else {
                return response()->json(['code' => 201, 'msg' => 'Data Berasil Disimpan']);
            }
        } catch (\Exception $exception) {
            return response()->json(['code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function submit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "filter_version" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $cr = ConsRate::where('version_id', $request->filter_version)->first();

            if (!$cr) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-submit data',
                    'message' => 'Data dengan versi tersebut tidak ditemukan!'
                ]);
            }

            $input['submited_by'] = auth()->user()->id;
            $input['submited_at'] = Carbon::now();
            $input['status_pengajuan'] = 'SUBMITTED';
            $input['rejected_by'] = null;
            $input['rejected_at'] = null;

            DB::transaction(function () use ($request, $input) {
                ConsRate::where('version_id', $request->filter_version)->update($input);
            });

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disubmit'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function approve(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "filter_version" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $cr = ConsRate::where('version_id', $request->filter_version)->first();

            if (!$cr) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-approve data',
                    'message' => 'Data dengan versi tersebut tidak ditemukan!'
                ]);
            }

            $input['approved_by'] = auth()->user()->id;
            $input['approved_at'] = Carbon::now();

            DB::transaction(function () use ($request, $input) {
                ConsRate::where('version_id', $request->filter_version)->update($input);
            });

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil diapprove'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function reject(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "filter_version" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $cr = ConsRate::where('version_id', $request->filter_version)->first();

            if (!$cr) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-reject data',
                    'message' => 'Data dengan versi tersebut tidak ditemukan!'
                ]);
            }

            $input['rejected_by'] = auth()->user()->id;
            $input['rejected_at'] = Carbon::now();
            $input['submited_by'] = null;
            $input['submited_at'] = null;

            DB::transaction(function () use ($request, $input) {
                ConsRate::where('version_id', $request->filter_version)->update($input);
            });

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil direject'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
