<?php

namespace App\Http\Controllers;

use App\DataTables\Master\BalansDataTable;
use App\Models\ConsRate;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalansController extends Controller
{
    public function index(Request $request, BalansDataTable $balansDataTable)
    {

        $antrian = antrian_material_balans(1);
//        dd($antrian);

        $query = DB::table('map_kategori_balans')
            ->select('map_kategori_balans.*', 'kategori_balans.kategori_balans')
            ->leftjoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
//            ->whereRaw("map_kategori_balans.material_code in ('MATERIAL 1', 'MATERIAL 2', 'MATERIAL 3', 'MATERIAL 4', 'MATERIAL 4')")
            ->whereIn('map_kategori_balans.material_code', ['MATERIAL 1', 'MATERIAL 2', 'MATERIAL 3', 'MATERIAL 4'])
            ->where('map_kategori_balans.version_id', 1)->get();

//        dd($query);

        if ($request->data == 'index') {
            return $balansDataTable->with(['antrian' => $antrian[0], 'version' => 1])->render('pages.buku_besar.balans.index');
        }elseif ($request->data == 'horizontal'){
            return $balansDataTable->render('pages.buku_besar.balans.index');
        }
        return view('pages.buku_besar.balans.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "tanggal" => 'required',
                "kategori_produk" => 'required',
                "biaya_penjualan" => 'required',
                "biaya_administrasi_umum" => 'required',
                "biaya_bunga" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            $check_data = LabaRugi::where('kategori_produk_id', $request->kategori_produk)
                ->where('periode', 'ilike', '%'.$request->tanggal.'%')
                ->first();

            $input['periode'] = $request->tanggal.'-01-01';
            $input['kategori_produk_id'] = $request->kategori_produk;
            $input['value_bp'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_penjualan));
            $input['value_bau'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_administrasi_umum));
            $input['value_bb'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_bunga));
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            if ($check_data != null){
                LabaRugi::where('id', $check_data->id)
                    ->update($input);
            }else{
                LabaRugi::create($input);
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
                "tanggal" => 'required',
                "kategori_produk" => 'required',
                "biaya_penjualan" => 'required',
                "biaya_administrasi_umum" => 'required',
                "biaya_bunga" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['periode'] = $request->tanggal.'-01-01';
            $input['kategori_produk_id'] = $request->kategori_produk;
            $input['value_bp'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_penjualan));
            $input['value_bau'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_administrasi_umum));
            $input['value_bb'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_bunga));
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            LabaRugi::where('id', $request->id)
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

            LabaRugi::where('id', $request->id)
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
        return Excel::download(new MS_LabaRugiExport(), 'laba rugi.xlsx');
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                "tanggal_import" => 'required',
            ], validatorMsg());

            if ($validator->fails()){
                return $this->makeValidMsg($validator);
            }

            $transaction = DB::transaction(function () use ($request){
                $empty_excel = Excel::toArray(new LabaRugiNewImport($request->tanggal_import), $request->file('file'));
                if ($empty_excel[0]){
                    $file = $request->file('file')->store('import');

                    LabaRugi::where('periode', 'ilike', '%'.$request->tanggal_import.'%')->delete();
                    $import = new LabaRugiNewImport($request->tanggal_import);
                    $import->import($file);

                    $data_fail = $import->failures();

                }else{
                    $data_fail = [];
                }
                return $data_fail;
            });

            if ($transaction->isNotEmpty()){
                return setResponse([
                    'code' => 500,
                    'title' => 'Gagal meng-import data',
                ]);
            }else{
                return setResponse([
                    'code' => 200,
                    'title' => 'Berhasil meng-import data'
                ]);
            }
        }catch (\Exception $exception){
//            dd($exception);
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function check(Request $request){
        try {
            $check = LabaRugi::where('periode', 'ilike', '%'.$request->periode.'%')
                ->first();

            if ($check == null){
                return response()->json(['code' => 200, 'msg' => 'Data Tidak Ada']);
            }else{
                return response()->json(['code' => 201, 'msg' => 'Data Ada']);
            }
        }catch (\Exception $exception){
            return response()->json(['code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
