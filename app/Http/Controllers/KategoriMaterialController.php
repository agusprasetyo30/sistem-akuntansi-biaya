<?php

namespace App\Http\Controllers;

use App\DataTables\Master\KategoriMaterialDataTable;
use App\Exports\Template\T_KategoriMaterialExport;
use App\Imports\KategoriMaterialImport;
use App\Models\KategoriMaterial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class KategoriMaterialController extends Controller
{
    public function index(Request $request, KategoriMaterialDataTable $kategorimaterialDataTable)
    {
        if ($request->data == 'index') {
            return $kategorimaterialDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.kategori_material.index');
        }
        return view('pages.master.kategori_material.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'deskripsi' => 'required',
                'is_active' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['kategori_material_name'] = $request->nama;
            $input['kategori_material_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            KategoriMaterial::create($input);

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
                'nama' => 'required',
                'deskripsi' => 'required',
                'is_active' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['kategori_material_name'] = $request->nama;
            $input['kategori_material_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            DB::table('kategori_material')
                ->where('id', $request->id)->update($input);

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
            $material = KategoriMaterial::get_kategori($request->id);

            if ($material) {
                return setResponse([
                    'code' => 400,
                    'title' => 'Kategori masih digunakan, kategori hanya bisa dinonaktifkan!'
                ]);
            } else {
                KategoriMaterial::where('id', $request->id)->delete();

                return setResponse([
                    'code' => 200,
                    'title' => 'Data berhasil dihapus'
                ]);
            }
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

            $file = $request->file('file')->store('import');
            $import = new KategoriMaterialImport;
            $import->import($file);
            $data_fail = $import->failures();

            if ($import->failures()->isNotEmpty()) {
                $err = [];

                foreach ($data_fail as $rows) {
                    $er = implode(' ', array_values($rows->errors()));
                    $hasil = $rows->values()[$rows->attribute()] . ' ' . $er;
                    array_push($err, $hasil);
                }
                // dd(implode(' ', $err));
                return setResponse([
                    'code' => 500,
                    'title' => 'Gagal meng-import data',
                    'message' => $err
                ]);
            }

            return setResponse([
                'code' => 200,
                'title' => 'Berhasil meng-import data'
            ]);
        } catch (Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new T_KategoriMaterialExport, 'kategori_material.xlsx');
    }
}
