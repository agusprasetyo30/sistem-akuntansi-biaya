<?php

namespace App\Http\Controllers;

use App\DataTables\Master\KategoriBalansDataTable;
use Illuminate\Http\Request;

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
            $validasi = $request->all();
            $validasi['kurs'] =str_replace('.', '', str_replace('Rp ', '', $request->kurs));

            $validator = Validator::make($validasi, [
                "tanggal" => 'required',
                "kurs" => 'required|min:0|not_in:0',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $date = explode("-",$request->tanggal);

            $input['month_year'] = $date[1].'-'.$date[0].'-01';
            $input['usd_rate'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->kurs));

            $check_data = Kurs::where('month_year', 'ilike', '%'.$date[1].'-'.$date[0].'-01'.'%')->first();

            DB::transaction(function () use ($input, $check_data){
                if ($check_data == null){
                    Kurs::create($input);
                }else{
                    Kurs::where('id', $check_data->id)->update($input);
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
            $validasi = $request->all();
            $validasi['kurs'] =str_replace('.', '', str_replace('Rp ', '', $request->kurs));

            $validator = Validator::make($validasi, [
                "tanggal" => 'required',
                "kurs" => 'required|min:0|not_in:0',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

//            dd($request);
            $date = explode("-",$request->tanggal);

            $input['month_year'] = $date[1].'-'.$date[0].'-01';
            $input['usd_rate'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->kurs));

            DB::transaction(function () use ($input, $request){
                Kurs::where('id', $request->id)
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
            Kurs::where('id', $request->id)
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
