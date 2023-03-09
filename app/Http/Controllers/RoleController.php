<?php

namespace App\Http\Controllers;

use App\DataTables\Master\RoleDataTable;
use App\Models\periode;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request, RoleDataTable $roleDataTable)
    {
        if ($request->data == 'index') {
            return $roleDataTable->render('pages.master.role.index');
        }
        return view('pages.master.role.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "role" => 'required',
                "status" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['nama_role'] = $request->role;
            $input['is_active'] = $request->status;
            $input['company_code'] = 'B000';

            Role::create($input);

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
                "status" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['nama_role'] = $request->role;
            $input['is_active'] = $request->status;
            $input['company_code'] = 'B000';

            Role::where('id', $request->id)->update($input);
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
            Role::where('id', $request->id)
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
