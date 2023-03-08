<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PermissionDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission as SpatiePermission;

class PermissionController extends Controller
{
    public function index(Request $request, PermissionDataTable $permissionDataTable)
    {
        if ($request->data == 'index') {
            return $permissionDataTable->render('pages.master.permission.index');
        }
        return view('pages.master.permission.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "permission" => 'required',
                // "status" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['name'] = $request->permission;
            // $input['guard_name'] = 'web';

            // Permission::create($input);
            SpatiePermission::create($input);

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
                "permission" => 'required',
                // "status" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['name'] = $request->permission;
            // $input['guard_name'] = 'web';

            SpatiePermission::where('id', $request->id)->update($input);
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
            SpatiePermission::where('id', $request->id)
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
}
