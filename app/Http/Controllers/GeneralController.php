<?php

namespace App\Http\Controllers;

use App\Models\ConsRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function mapping_akses(Request $request){
        try {

            $check = DB::table($request->db)->where('version_id', $request->version)
                ->where('company_code', $request->company)
                ->first();
            $map_akses = auth()->user()->mapping_akses($request->db);

            if ($check->status_pengajuan == 'DRAFT' or $check->status_pengajuan == 'REJECTED') {
                $code = 100;
                $msg = 'Data Draft';
            }elseif ($check->status_pengajuan == 'SUBMITTED') {
                $code = 101;
                $msg = 'Data Submitted';
            }elseif ($check->status_pengajuan == 'APPROVED'){
                $code = 102;
                $msg = 'Data Approved';
            }else{
                $code = 103;
                $msg = 'Data Tidak Ditemukan';
            }

            return response()->json(['code' => $code, 'msg' => $msg, 'akses' => $map_akses]);
        }catch (\Exception $exception){
            return response()->json(['code' => $exception->getCode(), 'msg' => $exception->getMessage(), 'akses' => null]);
        }
    }
}
