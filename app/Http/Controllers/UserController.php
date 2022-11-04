<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
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
