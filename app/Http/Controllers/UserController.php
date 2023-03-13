<?php

namespace App\Http\Controllers;

use App\DataTables\Master\UsersDataTable;
use App\Models\Management_Role;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request, UsersDataTable $usersDataTable)
    {
        if ($request->data == 'index') {
            return $usersDataTable->render('pages.master.user.index');
        }
        return view('pages.master.user.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "new_pass" => 'required|min:8',
                "nama" => 'required',
                "username" => 'required',
                "confirm_pass" => 'required|min:8',
            ], validatorMsg())
                ->setAttributeNames(
                    [
                        'new_pass' => 'Password',
                        'confirm_pass' => 'Konfirmasi Password',
                    ]
            );

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['name'] = $request->nama;
            $input['username'] = $request->username;
            $input['company_code'] = 'B000';

            if (trim($request->new_pass) == trim($request->confirm_pass)){
                $input['password'] = bcrypt(trim($request->new_pass));

                User::create($input);

                return setResponse([
                    'code' => 200,
                    'title' => 'Data berhasil disimpan'
                ]);
            }else{
                return response()->json(['code' => 202, 'msg' => 'Password Salah']);
            }

//            $input_management['role_id'] = (int) $request->role;
//            $input_management['login_method'] = $request->metode;
//            $input_management['username'] = $request->username;
//            $input_management['company_code'] = 'B000';
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
//                "role" => 'required',
                "nama" => 'required',
                "username" => 'required',
//                "metode" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['name'] = $request->nama;
            $input['username'] = $request->username;
            $input['company_code'] = 'B000';

            DB::transaction(function () use ($input, $request) {
                User::where('id', $request->id)
                    ->update($input);
            });
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
            Management_Role::where('user_id', $request->id)
                ->delete();
            User::where('id', $request->id)
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
