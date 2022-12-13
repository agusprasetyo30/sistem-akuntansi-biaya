<?php

namespace App\Http\Controllers;

use App\Models\GroupAccount;
use App\Models\KategoriMaterial;
use App\Models\KategoriProduk;
use App\Models\Kurs;
use App\Models\Material;
use App\Models\periode;
use App\Models\Plant;
use App\Models\Regions;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class SelectController extends Controller
{
    public function plant(Request $request)
    {
        $search = $request->search;
        //        dd($request);
        if ($search == '') {
            $plant = Plant::limit(10)
                ->get();
        } else {
            $plant = Plant::where('plant_code', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($plant as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->plant_code
            );
        }

        return response()->json($response);
    }

    public function periode(Request $request)
    {
        $search = $request->search;
        //        dd($request);
        if ($search == '') {
            $plant = Periode::limit(10)
                ->get();
        } else {
            $plant = periode::where('periode_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($plant as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->periode_name
            );
        }

        return response()->json($response);
    }

    public function kategori_material(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $kategori_material = KategoriMaterial::limit(10)
                ->where('is_active', 't')
                ->get();
        } else {
            $kategori_material = KategoriMaterial::where('kategori_material_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($kategori_material as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->kategori_material_name
            );
        }

        return response()->json($response);
    }

    public function group_account(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $group_account = GroupAccount::limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_account = GroupAccount::where('group_account_code', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        foreach ($group_account as $items) {
            $response[] = array(
                "id" => $items->group_account_code,
                "text" => $items->group_account_code . ' ' . $items->group_account_desc,
            );
        }

        return response()->json($response);
    }

    public function material(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $material = Material::limit(10)
                ->where('is_active', 't')
                ->get();
        } else {
            $material = Material::where('material_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($material as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->material_name
            );
        }

        return response()->json($response);
    }

    public function region(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $region = Regions::limit(10)
                ->where('is_active', 't')
                ->get();
        } else {
            $region = Regions::where('region_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($region as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->region_name
            );
        }

        return response()->json($response);
    }

    public function role(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $role = Role::limit(10)
                ->where('is_active', 't')
                ->get();
        } else {
            $role = Role::where('nama_role', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($role as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->nama_role
            );
        }

        return response()->json($response);
    }

    public function kurs(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $kurs = Kurs::limit(10)
                ->get();
        } else {
            $kurs = Kurs::where('year', 'ilike', '%' . $search . '%')
                ->orWhere('month', 'ilike', '%' . $search . '%')
                ->orWhere('usd_rate', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($kurs as $items) {
            $response[] = array(
                "id" => $items->usd_rate,
                "text" => $items->month . '/' . $items->year . ' - ' . rupiah($items->usd_rate)
            );
        }

        return response()->json($response);
    }



    //    Helper

    public function check_username(Request $request)
    {
        try {
            $data = User::where('username', $request->search)
                ->count();
            if ($data != 0) {
                return response()->json(['Code' => 201, 'msg' => 'Data Berasil Ditemukan']);
            } else {
                return response()->json(['Code' => 200, 'msg' => 'Data Tidak Tersedia']);
            }
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function check_email(Request $request)
    {
        try {
            $data = User::where('email', $request->search)
                ->count();
            if ($data != 0) {
                return response()->json(['Code' => 201, 'msg' => 'Data Berasil Ditemukan']);
            } else {
                return response()->json(['Code' => 200, 'msg' => 'Data Tidak Tersedia']);
            }
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function check_kurs(Request $request)
    {

        $data = explode('/', $request->periode);

        $kurs = DB::table('kurs')
            ->where('month', '=', check_month($data[0] - 1))
            ->where('year', '=', $data[1])
            ->first();

        if ($kurs == null) {
            return response()->json(['Code' => 200, 'data_kurs' => '']);
        } else {

            return response()->json(['Code' => 200, 'data_kurs' => $kurs->usd_rate]);
        }
    }

    public function check_kursv1(Request $request)
    {

        $data = Carbon::createFromFormat('Y-m-d', $request->periode)->format('Y-m-01 00:00:00');

        $asumsi = DB::table('asumsi_umum')
            ->where('month_year', '=', $data)
            ->where('version_id', '=', $request->id)
            ->first();

        if ($asumsi == null) {
            return response()->json(['Code' => 200, 'data_kurs' => '']);
        } else {

            return response()->json(['Code' => 200, 'data_kurs' => $asumsi->usd_rate]);
        }
    }
}
