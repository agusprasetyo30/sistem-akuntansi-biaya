<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ManagementUserRoleDataTable;
use App\Models\ManagementUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ManagementUserRoleController extends Controller
{
    public function index(Request $request, ManagementUserRoleDataTable $usersDataTable){
        if ($request->data == 'index'){
            return $usersDataTable->render('pages.master.management_user_role.index');
        }
        return view('pages.master.management_user_role.index');
    }

    public function create(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "role" => 'required',
                "user" => 'required',
                "metode" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['user_id'] = $request->user;
            $input['role_id'] = (int) $request->role;
            $input['login_method'] = $request->metode;

            DB::transaction(function () use ($input){
                ManagementUserRole::create($input);
            });

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
                "role" => 'required',
                "user" => 'required',
                "metode" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['user_id'] = $request->user;
            $input['role_id'] = (int) $request->role;
            $input['login_method'] = $request->metode;

            DB::transaction(function () use ($input, $request){
                ManagementUserRole::where('id', $request->id)->update($input);
            });
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
            ManagementUserRole::where('id', $request->id)->delete();

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
