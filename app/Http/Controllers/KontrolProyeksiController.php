<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KontrolProyeksiController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.kontrol_proyeksi.index');
    }
}
