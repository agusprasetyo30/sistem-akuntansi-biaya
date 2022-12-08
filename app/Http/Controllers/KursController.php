<?php

namespace App\Http\Controllers;

use App\DataTables\Master\KursDataTable;
use App\DataTables\Master\UsersDataTable;
use App\Models\Kurs;
use App\Models\Management_Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $request->validate([
                "tanggal" => 'required',
                "kurs" => 'required',
            ]);

            $date = explode("-",$request->tanggal);

            $input['month'] = $date[0];
            $input['year'] = $date[1];
            $input['usd_rate'] = (double) str_replace(',','.',str_replace('.','',$request->kurs));

//            dd($input);
            DB::transaction(function () use ($input){
                Kurs::create($input);
            });

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
//            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "tanggal" => 'required',
                "kurs" => 'required',
            ]);

//            dd($request);
            $date = explode("-",$request->tanggal);

            $input['month'] = $date[0];
            $input['year'] = $date[1];
            $input['usd_rate'] = (double) str_replace(',','.',str_replace('.','',str_replace('Rp ', '', $request->kurs)));

//            dd($input);
            DB::transaction(function () use ($input, $request){
                Kurs::where('id', $request->id)
                    ->update($input);
            });
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Kurs::where('id', $request->id)
                ->delete();
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
