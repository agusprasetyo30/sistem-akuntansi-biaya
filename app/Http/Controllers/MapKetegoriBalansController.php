<?php

namespace App\Http\Controllers;

use App\DataTables\Master\MapKategoriBalansDataTable;
use App\Models\MapKategoriBalans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MapKetegoriBalansController extends Controller
{
    public function index(Request $request, MapKategoriBalansDataTable $mapKategoriBalansDataTable){
        if ($request->data == 'index'){
            return $mapKategoriBalansDataTable->render('pages.master.mapping_balans.index');
        }
        return view('pages.master.mapping_balans.index');
    }

    public function create(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                "versi" => 'required',
                "material_balans" => 'required',
                "kategori_balans" => 'required|min:0|not_in:0',
                "plant" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $result = '';

            foreach ($request->plant as $item){
                $result .= $item.';';
            }

            $input['version_id'] = $request->versi;
            $input['material_code'] = $request->material_balans;
            $input['kategori_balans_id'] = $request->kategori_balans;
            $input['plant_code'] = $result;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            $check_data = MapKategoriBalans::where([
                'version_id' => $request->versi,
                'kategori_balans_id' => $request->kategori_balans,
                'company_code' => auth()->user()->company_code,
                'material_code' => $request->material_balans
            ])->first();

            DB::transaction(function () use ($input, $check_data){
                if ($check_data == null){
                    MapKategoriBalans::create($input);
                }else{
                    MapKategoriBalans::where('id', $check_data->id)->update($input);
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

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "versi" => 'required',
                "material_balans" => 'required',
                "kategori_balans" => 'required|min:0|not_in:0',
                "plant" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $result = '';

            foreach ($request->plant as $item){
                $result .= $item.';';
            }

            $input['version_id'] = $request->versi;
            $input['material_code'] = $request->material_balans;
            $input['kategori_balans_id'] = $request->kategori_balans;
            $input['plant_code'] = $result;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            DB::transaction(function () use ($input, $request){

                $check_data = MapKategoriBalans::where([
                    'version_id' => $request->versi,
                    'kategori_balans_id' => $request->kategori_balans,
                    'company_code' => auth()->user()->company_code,
                    'material_code' => $request->material_balans
                ])->first();

                if ($check_data == null){
                    MapKategoriBalans::where('id', $check_data->id)->update($input);
                }else{
                    MapKategoriBalans::where('id', $check_data->id)->delete();
                    MapKategoriBalans::where('id', $check_data->id)->update($input);
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
            MapKategoriBalans::where('id', $request->id)
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
}
