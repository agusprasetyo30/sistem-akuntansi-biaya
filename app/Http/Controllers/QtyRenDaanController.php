<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_QtyRenDaanDataTable;
use App\DataTables\Master\QtyRenDaanDataTable;
use App\Exports\MultipleSheet\MS_KuantitiRenDaanExport;
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
            return $qtyrendaanDataTable->render('pages.buku_besar.qty_rendaan.index');
        }elseif ($request->data == 'horizontal'){
            return $h_QtyRenDaanDataTable->with(['version' => $request->version])->render('pages.buku_besar.qty_rendaan.index');
        }elseif ($request->data == 'version'){
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id',$request->version)
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

            if ($validator->fails())
                return $this->makeValidMsg($validator);

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
            $input['qty_rendaan_value'] = (double) $request->qty_rendaan_value;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            if ($check_data != null){
                QtyRenDaan::where('id', $check_data->id)
                    ->update($input);
            }else{
                QtyRenDaan::create($input);
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
                "qty_rendaan_value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_name'] = $request->region_id;
            $input['qty_rendaan_value'] = (double) $request->qty_rendaan_value;
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

    public function import(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                "file" => 'required',
                "version" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            DB::transaction(function () use ($request){
                QtyRenDaan::where('version_id', $request->version)->delete();

                $file = $request->file('file')->store('import');

                $import = new KuantitiRenDaanImport($request->version);
                $import->import($file);

                $data_fail = $import->failures();
                if ($data_fail->isNotEmpty()){
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
        }catch (\Exception $exception){
            dd($exception);
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

    public function check(Request $request){
        try {
            $check = QtyRenDaan::where('version_id', $request->version)
                ->first();
            if ($check == null){
                return response()->json(['Code' => 200, 'msg' => 'Data Tidak Ada']);
            }else{
                return response()->json(['Code' => 201, 'msg' => 'Data Ada']);
            }
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
