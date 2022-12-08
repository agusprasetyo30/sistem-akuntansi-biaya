<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ProdukDataTable;
use App\Exports\ProdukExport;
use App\Imports\ProdukImport;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ProdukController extends Controller
{
    public function index(Request $request, ProdukDataTable $produkDataTable)
    {
        if ($request->data == 'index') {
            return $produkDataTable->render('pages.master.produk.index');
        }
        return view('pages.master.produk.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "produk_name" => 'required',
                "produk_desc" => 'required',
                "kategori_produk_id" => 'required',
                "is_dummy" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['produk_name'] = $request->produk_name;
            $input['produk_desc'] = $request->produk_desc;
            $input['kategori_produk_id'] = $request->kategori_produk_id;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Produk::create($input);

            // return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
            return setResponse([
                'code' => 200,
                'title' => 'Data Berhasil Disimpan'
            ]);
        } catch (\Exception $error) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "produk_name" => 'required',
                "produk_desc" => 'required',
                "kategori_produk_id" => 'required',
                "is_dummy" => 'required',
                "is_active" => 'required',
            ]);

            $input['produk_name'] = $request->produk_name;
            $input['produk_desc'] = $request->produk_desc;
            $input['kategori_produk_id'] = $request->kategori_produk_id;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('produk')->where('id', $request->id)->update($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data Berhasil Disimpan'
            ]);
        } catch (\Exception $error) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Produk::where('id', $request->id)
                ->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => auth()->user()->id
                ]);
            return setResponse([
                'code' => 200,
                'title' => 'Data Berhasil Disimpan',
            ]);
        } catch (\Exception $error) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('file')->store('import');
            $import = new ProdukImport;
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
                return response()->json(['Code' => 500, 'msg' => $err]);
            }

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function export()
    {
        return Excel::download(new ProdukExport, 'produk.xlsx');
    }
}
