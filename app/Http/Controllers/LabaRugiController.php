<?php

namespace App\Http\Controllers;

use App\DataTables\Master\labaRugiDataTable;
use App\Exports\MultipleSheet\MS_LabaRugiExport;
use App\Exports\Vertical\KursExport;
use App\Exports\Vertical\LabaRugiExport;
use App\Imports\LabaRugiImport;
use App\Imports\LabaRugiNewImport;
use App\Models\KategoriProduk;
use App\Models\LabaRugi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LabaRugiController extends Controller
{
    public function index(Request $request, labaRugiDataTable $labaRugiDataTable)
    {
        if ($request->data == 'index') {
            return $labaRugiDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.laba_rugi.index');
        } elseif ($request->data == 'horizontal') {
            return $labaRugiDataTable->render('pages.buku_besar.laba_rugi.index');
        }
        return view('pages.buku_besar.laba_rugi.index');
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
                ->where('version_id', $request->tanggal)
                ->first();

            $input['version_id'] = $request->tanggal;
            $input['kategori_produk_id'] = $request->kategori_produk;
            $input['value_bp'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_penjualan));
            $input['value_bau'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_administrasi_umum));
            $input['value_bb'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->biaya_bunga));
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            if ($check_data != null) {
                LabaRugi::where('id', $check_data->id)
                    ->update($input);
            } else {
                LabaRugi::create($input);
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
                "tanggal" => 'required',
                "kategori_produk" => 'required',
                "biaya_penjualan" => 'required',
                "biaya_administrasi_umum" => 'required',
                "biaya_bunga" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['version_id'] = $request->tanggal;
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
                'title' => $exception->getMessage()
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
                "data_main_version_import" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $transaction = DB::transaction(function () use ($request){
                $empty_excel = Excel::toArray(new LabaRugiNewImport($request->data_main_version_import), $request->file('file'));
                if ($empty_excel[0]){
                    $file = $request->file('file')->store('import');

                    LabaRugi::where('version_id', $request->data_main_version_import)->delete();
                    $import = new LabaRugiNewImport($request->data_main_version_import);
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
        }catch (\Exception $exception){
//            dd($exception);
            if ($exception->getCode() == 23503){
                $empty_excel = Excel::toArray(new LabaRugiNewImport($request->tanggal_import), $request->file('file'));

                $kategori_produk = [];
                $kategori_produk_ = [];

                foreach ($empty_excel[0] as $key => $value) {
                    array_push($kategori_produk, 'Kategori Produk ID ' . $value['kategori_produk_id'] . ' tidak ada pada master');
                    $d_kkategori_produk = KategoriProduk::whereIn('id', [$value['kategori_produk_id']])->first();
                    if ($d_kkategori_produk) {
                        array_push($kategori_produk_, 'Kategori Produk ID ' . $d_kkategori_produk->kategori_produk_id . ' tidak ada pada master');
                    }
                }

                $result_kategori_produk = array_diff($kategori_produk, $kategori_produk_);
                $result = array_merge($result_kategori_produk);
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
                }
            } else {
                return setResponse([
                    'code' => 400,
                    'title' => $exception->getMessage()
                ]);
            }
        }
    }

    public function export_horizontal(Request $request)
    {
        $labarugi = DB::table('laba_rugi')
            ->select('laba_rugi.*', 'version_asumsi.version', DB::Raw("CONCAT(kategori_produk.kategori_produk_name, ' ', kategori_produk.kategori_produk_desc) prod"))
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'laba_rugi.version_id')
            ->leftjoin('kategori_produk', 'kategori_produk.id', '=', 'laba_rugi.kategori_produk_id')
            ->whereNull('laba_rugi.deleted_at')
            ->orderBy('prod');

        if($request->version != 'all') {
            $labarugi->where('a.version_id', $request->version);
        }

        try {
            $labarugi = $labarugi
                    ->get()
                    ->toArray();
        } catch (\Throwable $th) {
            $labarugi = [];
        }
        // return response()->json($labarugi);

        $data = [
            'labarugi' => $labarugi
        ];
        return Excel::download(new LabaRugiExport($data), "Laba Rugi.xlsx");
    }

    public function check(Request $request){
        try {
            $check = LabaRugi::where('version_id', $request->periode)
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
