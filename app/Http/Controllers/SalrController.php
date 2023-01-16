<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_SalrDataTable;
use App\DataTables\Master\SalrDataTable;
use App\Exports\MultipleSheet\MS_SalrExport;
use App\Imports\SalrImport;
use App\Models\Salr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SalrController extends Controller
{
    public function index(Request $request, SalrDataTable $salrDataTable, H_SalrDataTable $h_SalrDataTable)
    {
        if ($request->data == 'index') {
            return $salrDataTable->render('pages.buku_besar.salr.index');
        }elseif ($request->data == 'horizontal'){
            return $h_SalrDataTable->render('pages.buku_besar.salr.index');
        }
        return view('pages.buku_besar.salr.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "ga_account" => 'required',
                "gl_account" => 'required',
                "cost_center" => 'required',
                "tanggal" => 'required',
                "value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $timestamps = explode('-', $request->tanggal);

            $input['group_account_fc'] = $request->ga_account;
            $input['gl_account_fc'] = $request->gl_account;
            $input['cost_center'] = $request->cost_center;
            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_id;
            $input['periode'] = $timestamps[1].'-'.$timestamps[0].'-01';
            $input['value'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->value));
            $input['name'] = $request->nama;
            $input['partner_cost_center'] = $request->partner_cost_center;
            $input['username'] = $request->username;
            $input['material_code'] = $request->material;
            $input['document_number'] = $request->document_num;
            $input['document_number_text'] = $request->document_num_desc;
            $input['purchase_order'] = $request->purchase_order;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            Salr::create($input);

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
                "ga_account" => 'required',
                "gl_account" => 'required',
                "cost_center" => 'required',
                "tanggal" => 'required',
                "value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $timestamps = explode('-', $request->tanggal);

            $input['group_account_fc'] = $request->ga_account;
            $input['gl_account_fc'] = $request->gl_account;
            $input['cost_center'] = $request->cost_center;
            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_id;
            $input['periode'] = $timestamps[1].'-'.$timestamps[0].'-01';
            $input['value'] = (double) str_replace('.', '', str_replace('Rp ', '', $request->value));
            $input['name'] = $request->nama;
            $input['partner_cost_center'] = $request->partner_cost_center;
            $input['username'] = $request->username;
            $input['material_code'] = $request->material;
            $input['document_number'] = $request->document_num;
            $input['document_number_text'] = $request->document_num_desc;
            $input['purchase_order'] = $request->purchase_order;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            Salr::where('id', $request->id)
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

            Salr::where('id', $request->id)
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
        return Excel::download(new MS_SalrExport(), 'SALR.xlsx');
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                'tanggal_import' => 'required'
            ], validatorMsg());

            if ($validator->fails()){
                return $this->makeValidMsg($validator);
            }

            $transaction = DB::transaction(function () use ($request){
                $temp = explode('-', $request->tanggal_import);
                $timestamp = $temp[1].'-'.$temp[0].'-01';
                $empty_excel = Excel::toArray(new SalrImport($timestamp), $request->file('file'));
                if ($empty_excel[0]){
                    $file = $request->file('file')->store('import');


                    Salr::where('periode', 'ilike', '%'.$timestamp.'%')->delete();
                    $import = new SalrImport($timestamp);
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
            $timestamp = explode('-', $request->periode);
            $check = Salr::where('periode', 'ilike', '%'.$timestamp[1].'-'.$timestamp[0].'-01'.'%')
                ->first();
            if ($check == null){
                return response()->json(['code' => 200, 'msg' => 'Data Tidak Ada']);
            }else{
                return response()->json(['code' => 201, 'msg' => 'Data Ada']);
            }
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
