<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_TotalDaanDataTable;
use App\DataTables\Master\TotalDaanDataTable;
use App\Models\TotalDaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class TotalDaanController extends Controller
{
    public function index(Request $request, TotalDaanDataTable $totaldaanDataTable, H_TotalDaanDataTable $h_TotalDaanDataTable)
    {
        if ($request->data == 'index') {
            return $totaldaanDataTable->render('pages.buku_besar.total_daan.index');
        } elseif ($request->data == 'horizontal') {
            return $h_TotalDaanDataTable->with(['version' => $request->version, 'val' => $request->val])->render('pages.buku_besar.total_daan.index');
        } elseif ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();
            return response()->json(['code' => 200, 'asumsi' => $asumsi]);
        }
        return view('pages.buku_besar.total_daan.index');
    }
}
