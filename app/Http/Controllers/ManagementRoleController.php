<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ManagementRoleDataTable;
use App\Models\Management_Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManagementRoleController extends Controller
{
    public function index(Request $request, ManagementRoleDataTable $mroleDataTable)
    {
        if ($request->data == 'index') {
            return $mroleDataTable->render('pages.master.management_role.index');
        }

        return view('pages.master.management_role.index');
    }

    public function create(Request $request)
    {
//        dd($request);
        try {
            $validator = Validator::make($request->all(), [
                "role" => 'required',
                "user" => 'required',
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

            $user = DB::table('users')->where('id', $request->user)
                ->first();

            $input['user_id'] = $request->user;
            $input['username'] = $user->username;
            $input['role_id'] = $request->role;
            $input['kode_feature'] = $request->menu;
            $input['create'] = $request->create == 1 ? true:false;
            $input['read'] = $request->read == 1 ? true:false;
            $input['update'] = $request->update == 1 ? true:false;
            $input['delete'] = $request->delete == 1 ? true:false;
            $input['approve'] = $request->approve == 1 ? true:false;
            $input['submit'] = $request->submit == 1 ? true:false;

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
//        dd($request);
        try {
            $validator = Validator::make($request->all(), [
                "role" => 'required',
                "user" => 'required',
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

            $user = DB::table('users')->where('id', $request->user)
                ->first();

            $input['user_id'] = $request->user;
            $input['username'] = $user->username;
            $input['role_id'] = $request->role;
            $input['kode_feature'] = $request->menu;
            $input['create'] = $request->create == 1 ? true:false;
            $input['read'] = $request->read == 1 ? true:false;
            $input['update'] = $request->update == 1 ? true:false;
            $input['delete'] = $request->delete == 1 ? true:false;
            $input['approve'] = $request->approve == 1 ? true:false;
            $input['submit'] = $request->submit == 1 ? true:false;

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
}
