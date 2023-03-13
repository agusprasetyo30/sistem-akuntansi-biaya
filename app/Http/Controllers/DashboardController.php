<?php

namespace App\Http\Controllers;

use App\Models\Management_Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.dashboard.index');
    }

    public function profile($id){
        $data_id = User::select('users.company_code', 'company.company_name', 'users.name', 'users.id')
            ->leftjoin('company', 'company.company_code', '=', 'users.company_code')
            ->where('users.id', decrypt($id))
            ->first();

        $data_role = Management_Role::select('role.nama_role', 'management_role.role_id')
            ->where('user_id', decrypt($id))
            ->leftJoin('role', 'role.id', '=', 'management_role.role_id')
            ->get();

        return view('pages.profile.index', compact('data_id', 'data_role'));
    }

    public function check_pass(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => 'required',
            "old_pass" => 'required|min:8',
            "new_pass" => 'required|min:8',
            "confirm_pass" => 'required|min:8',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        $cek_user = User::where('id', $request->id)->first();

        if (Hash::check($request->old_pass, $cek_user->password)){
            if (trim($request->new_pass) == trim($request->confirm_pass)){
                User::where('id', $request->id)->update([
                    'password' => bcrypt(trim($request->new_pass))
                ]);
                return setResponse([
                    'code' => 200,
                    'title' => 'Berhasil Mengubah Password'
                ]);
            }else{
                return response()->json(['code' => 202, 'msg' => 'Password Salah']);
            }

        }else{
            return response()->json(['code' => 201, 'msg' => 'Password Lama  Salah']);
        }
    }
}
