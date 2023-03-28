<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function mapping_akses(Request $request){
        try {

            $map_akses = auth()->user()->mapping_akses($request->db);
            $result = $map_akses;
            dd($request->db, $result);
            return $result;
        }catch (\Exception $exception){
            $result = [];
            return $result;
        }
    }
}
