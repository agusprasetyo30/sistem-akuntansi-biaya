<?php

namespace App\Http\Controllers;

use App\DataTables\Master\RoleDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
    public function index(Request $request, RoleDataTable $roleDataTable)
    {
        $permission = Permission::get();
        if ($request->data == 'index') {
            return $roleDataTable->render('pages.master.role.index');
        }
        return view('pages.master.role.index', compact('permission'));
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "role" => 'required|unique:roles,name',
                "permission" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['name'] = $request->role;

            $role = SpatieRole::create($input);
            $role->syncPermissions($request->input('permission'));

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
                "role" => 'required',
                "permission" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            // $input['name'] = $request->role;
            // dd($request->permission, $request->role);

            // $role = SpatieRole::where('id', $request->id)->update($input);

            $role = SpatieRole::find($request->id);
            $role->name = $request->input('role');
            $role->save();

            $role->syncPermissions($request->input('permission'));

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
            SpatieRole::where('id', $request->id)
                ->delete();
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

    public function givePermission(Request $request)
    {
        $role = SpatieRole::where('id', $request->id)->first();
        $permission = Permission::where('id', $request->permission)->first();

        if ($role->hasPermissionTo($permission->name)) {
            return setResponse([
                'code' => 430,
                'title' => 'Permission sudah ada!',
                'message' => 'Role sudah memiliki permission tersebut'
            ]);
        }

        $role->givePermissionTo($permission->name);
        return setResponse([
            'code' => 200,
            'title' => 'Permission berhasil ditambahkan'
        ]);
    }

    public function revokePermission(Request $request)
    {
        $role = SpatieRole::where('id', $request->id)->first();
        $permission = Permission::where('id', $request->permission)->first();

        if ($role->hasPermissionTo($permission->name)) {
            $role->revokePermissionTo($permission->name);
            return setResponse([
                'code' => 200,
                'title' => 'Permission berhasil revoke'
            ]);
        }

        return setResponse([
            'code' => 430,
            'title' => 'Permission tidak ada!',
            'message' => 'Permission tidak ditemukan'
        ]);
    }
}
