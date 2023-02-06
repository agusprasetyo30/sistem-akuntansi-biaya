<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SimulasiProyeksiDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimulasiProyeksiController extends Controller
{
    public function index(Request $request, SimulasiProyeksiDataTable $simulasiproyeksiDatatable)
    {
        return $simulasiproyeksiDatatable->render('pages.simulasi_proyeksi.index');
    }
}
