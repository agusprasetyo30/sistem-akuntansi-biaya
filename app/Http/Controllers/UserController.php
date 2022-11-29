<?php

namespace App\Http\Controllers;

use App\DataTables\Master\UsersDataTable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, UsersDataTable $usersDataTable){
        if ($request->data == 'index'){
            return $usersDataTable->render('pages.master.user.index');
        }
        return view('pages.master.user.index');
    }
    // public function index(Request $request)
    // {
    //     return view('planning-tools.guestlist.index');
    // }

    // public function grid(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $userId = auth()->user()->id;
    //         $member = Member::where('user_id', $userId)->first();

    //         return createTables($request, [
    //             'model' => new Guestlist(),
    //             'func' => 'grid',
    //             'condition' => [$member->id]
    //         ]);
    //     }

    //     return redirect()->route('planning-tools-guestlist');
    // }
}
