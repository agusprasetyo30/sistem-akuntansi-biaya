<?php

namespace App\Http\Controllers;

use App\DataTables\Master\KursDataTable;
use App\DataTables\Master\UsersDataTable;
use App\Models\Kurs;
use App\Models\Management_Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KursController extends Controller
{
    public function index(Request $request, KursDataTable $kursDataTable){
        if ($request->data == 'index'){
            return $kursDataTable->render('pages.master.kurs.index');
        }
        return view('pages.master.kurs.index');
    }

    public function create(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "tanggal" => 'required',
                "kurs" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $date = explode("-",$request->tanggal);

            $input['month_year'] = $date[1].'-'.$date[0].'-01';
            $input['usd_rate'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->kurs));

            DB::transaction(function () use ($input){
                Kurs::create($input);
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
                "tanggal" => 'required',
                "kurs" => 'required',
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
