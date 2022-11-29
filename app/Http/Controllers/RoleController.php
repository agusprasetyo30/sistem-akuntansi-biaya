<?php

namespace App\Http\Controllers;

use App\DataTables\Master\RoleDataTable;
use App\Models\periode;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request, RoleDataTable $roleDataTable){
        if ($request->data == 'index'){
            return $roleDataTable->render('pages.master.role.index');
        }
        return view('pages.master.role.index');
    }

    public function create(Request $request){
        try {
            $request->validate([
                "role" => 'required',
                "status" => 'required',
            ]);

            $input['nama_role'] = $request->role;
            $input['is_active'] = $request->status;

            Role::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "role" => 'required',
                "status" => 'required',
            ]);

            $input['nama_role'] = $request->role;
            $input['is_active'] = $request->status;

            Role::where('id', $request->id)->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Role::where('id', $request->id)
                ->delete();
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
