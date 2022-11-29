<?php

namespace App\Http\Controllers;

use App\DataTables\Master\UsersDataTable;
use App\Models\Management_Role;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request, UsersDataTable $usersDataTable){
        if ($request->data == 'index'){
            return $usersDataTable->render('pages.master.user.index');
        }
        return view('pages.master.user.index');
    }

    public function create(Request $request){
        try {
            $request->validate([
                "role" => 'required',
                "nama" => 'required',
                "username" => 'required',
                "email" => 'required',
                "metode" => 'required',
            ]);

            $input['name'] = $request->nama;
            $input['username'] = $request->username;
            $input['email'] = $request->email;
            $input['password'] = bcrypt('Petrokimia1');

            $input_management['role_id'] = (int) $request->role;
            $input_management['login_method'] = $request->metode;
            $input_management['username'] = $request->username;

            DB::transaction(function () use ($input, $input_management){
                $user = User::create($input);

                $input_management['user_id'] = $user->id;

                Management_Role::create($input_management);
            });

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
//            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "role" => 'required',
                "nama" => 'required',
                "username" => 'required',
                "email" => 'required',
                "metode" => 'required',
            ]);

            $input['name'] = $request->nama;
            $input['username'] = $request->username;
            $input['email'] = $request->email;
            $input['password'] = bcrypt('Petrokimia1');

            $input_management['role_id'] = (int) $request->role;
            $input_management['login_method'] = $request->metode;
            $input_management['username'] = $request->username;

            DB::transaction(function () use ($input, $input_management, $request){
                User::where('id', $request->id)
                    ->update($input);

                Management_Role::where('user_id', $request->id)
                    ->update($input_management);
            });
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Management_Role::where('user_id', $request->id)
                ->delete();
            User::where('id', $request->id)
                ->delete();
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
