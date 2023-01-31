<?php

namespace App\Http\Controllers;

use App\DataTables\Master\KategoriBalansDataTable;
use App\Models\KategoriBalans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KategoriBalansController extends Controller
{
    public function index(Request $request, KategoriBalansDataTable $kategoriBalansDataTable){
        if ($request->data == 'index'){
            return $kategoriBalansDataTable->render('pages.master.kategori_balans.index');
        }
        return view('pages.master.kategori_balans.index');
    }

    public function create(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                "kategori_balans" => 'required',
                "kategori_balans_desc" => 'required|min:0|not_in:0',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['kategori_balans'] = $request->kategori_balans;
            $input['kategori_balans_desc'] = $request->kategori_balans_desc;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            $check_data = KategoriBalans::where([
                'kategori_balans' => $request->kategori_balans,
                'company_code' => auth()->user()->company_code
            ])->first();

            DB::transaction(function () use ($input, $check_data){
                if ($check_data == null){
                    KategoriBalans::create($input);
                }else{
                    KategoriBalans::where('id', $check_data->id)->update($input);
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
                "kategori_balans" => 'required',
                "kategori_balans_desc" => 'required|min:0|not_in:0',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['kategori_balans'] = $request->kategori_balans;
            $input['kategori_balans_desc'] = $request->kategori_balans_desc;
            $input['company_code'] = auth()->user()->company_code;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::transaction(function () use ($input, $request){
                KategoriBalans::where('id', $request->id)
                    ->update($input);
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
            KategoriBalans::where('id', $request->id)
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
