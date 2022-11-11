<?php

namespace App\Http\Controllers;

use App\DataTables\Master\KategoriMaterialDataTable;
use App\Models\KategoriMaterial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriMaterialController extends Controller
{
    public function index(Request $request, KategoriMaterialDataTable $kategorimaterialDataTable){
        if ($request->data == 'index'){
            return $kategorimaterialDataTable->render('pages.master.kategori_material.index');
        }
        return view('pages.master.kategori_material.index');
    }

    public function create(Request $request){
        try {
            $request->validate([
                "nama" => 'required',
                "deskripsi" => 'required',
                "is_active" => 'required',
            ]);


            $input['kategori_material_name'] = $request->nama;
            $input['kategori_material_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            KategoriMaterial::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                "nama" => 'required',
                "deskripsi" => 'required',
                "is_active" => 'required',
            ]);

            $input['kategori_material_name'] = $request->nama;
            $input['kategori_material_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            DB::table('kategori_material')
                ->where('id', $request->id)->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request){
        try {
            KategoriMaterial::where('id', $request->id)
                ->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => auth()->user()->id
                ]);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}