<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ManagementUserAksesDataTable;
use App\Models\Management_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManagementUserAksesController extends Controller
{
    public function index(Request $request, ManagementUserAksesDataTable $mroleDataTable)
    {
        //        dd(auth()->user()->mapping_akses('users'));
        //        dd(array_diff(auth()->user()->mapping_side_bar_akses(), [1,2,3,4]));
        //        , count(auth()->user()->mapping_side_bar_akses(), count(array_diff([10], auth()->user()->mapping_side_bar_akses())) != count(auth()->user()->mapping_side_bar_akses())
        //        dd(count(array_diff([10], auth()->user()->mapping_side_bar_akses()))  , count(auth()->user()->mapping_side_bar_akses()) , count(array_diff([10,11,12,13], auth()->user()->mapping_side_bar_akses()))  != count(auth()->user()->mapping_side_bar_akses())  );

        if ($request->data == 'index') {
            return $mroleDataTable->render('pages.master.management_user_akses.index');
        }

        return view('pages.master.management_user_akses.index');
    }

    public function create(Request $request)
    {
        // dd($request);
        try {
            $validator = Validator::make($request->all(), [
                "role" => 'required',
                "menu" => 'required',
                "create" => 'required',
                "read" => 'required',
                "update" => 'required',
                "delete" => 'required',
                "approve" => 'required',
                "submit" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $role_menu = Management_Role::where('role_id', $request->role)->where('kode_feature', $request->menu)->first();
            if ($role_menu) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal menambahkan data',
                    'message' => 'Role sudah mempunyai akses menu yang dipilih!'
                ]);
            }

            $feature = DB::table('feature')->where('kode_unik', $request->menu)
                ->first();

            $input['role_id'] = $request->role;
            $input['kode_feature'] = $request->menu;
            $input['db'] = $feature->db;
            $input['company_code'] = auth()->user()->company_code;
            $input['create'] = $request->create == 1 ? true : false;
            $input['read'] = $request->read == 1 ? true : false;
            $input['update'] = $request->update == 1 ? true : false;
            $input['delete'] = $request->delete == 1 ? true : false;
            $input['approve'] = $request->approve == 1 ? true : false;
            $input['submit'] = $request->submit == 1 ? true : false;

            Management_Role::create($input);

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
        // dd($request);
        try {
            $validator = Validator::make($request->all(), [
                "role" => 'required',
                "menu" => 'required',
                "create" => 'required',
                "read" => 'required',
                "update" => 'required',
                "delete" => 'required',
                "approve" => 'required',
                "submit" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $feature = DB::table('feature')->where('kode_unik', $request->menu)
                ->first();

            $input['role_id'] = $request->role;
            $input['kode_feature'] = $request->menu;
            $input['db'] = $feature->db;
            $input['company_code'] = auth()->user()->company_code;
            $input['create'] = $request->create == 1 ? true : false;
            $input['read'] = $request->read == 1 ? true : false;
            $input['update'] = $request->update == 1 ? true : false;
            $input['delete'] = $request->delete == 1 ? true : false;
            $input['approve'] = $request->approve == 1 ? true : false;
            $input['submit'] = $request->submit == 1 ? true : false;

            Management_Role::where('id', $request->id)->update($input);
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
            Management_Role::where('id', $request->id)
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

    public function validasi_akses($feature)
    {
        $validasi_akses = false;

        if (auth()->user()->mapping_akses($feature) != null) {
            $validasi_akses = true;
        }

        return $validasi_akses;
    }
}
