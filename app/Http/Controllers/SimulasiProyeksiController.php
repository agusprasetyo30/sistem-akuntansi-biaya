<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimulasiProyeksiController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.simulasi_proyeksi.index');
    }
}
