<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.master.tarif.index');
    }
}
