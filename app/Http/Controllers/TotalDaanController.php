<?php

namespace App\Http\Controllers;

use App\DataTables\Master\TotalDaanDataTable;
use App\Models\TotalDaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TotalDaanController extends Controller
{
    public function index(Request $request, TotalDaanDataTable $totaldaanDataTable)
    {
        if ($request->data == 'index') {
            return $totaldaanDataTable->render('pages.buku_besar.total_daan.index');
        }
        return view('pages.buku_besar.total_daan.index');
    }
}
