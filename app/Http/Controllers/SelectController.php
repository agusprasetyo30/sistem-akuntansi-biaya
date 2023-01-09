<?php

namespace App\Http\Controllers;

use App\Models\Asumsi_Umum;
use App\Models\GroupAccount;
use App\Models\GroupAccountFC;
use App\Models\KategoriMaterial;
use App\Models\KategoriProduk;
use App\Models\Kurs;
use App\Models\Material;
use App\Models\Plant;
use App\Models\Regions;
use App\Models\Role;
use App\Models\User;
use App\Models\Version_Asumsi;
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
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $plant = Plant::where('plant_code', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        foreach ($plant as $items) {
            $response[] = array(
                "id" => $items->plant_code,
                "text" => $items->plant_code
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

    public function group_account_fc(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $group_account = GroupAccountFC::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_account = GroupAccountFC::where('group_account_fc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        foreach ($group_account as $items) {
            $response[] = array(
                "id" => $items->group_account_fc,
                "text" => $items->group_account_fc . ' ' . $items->group_account_fc_desc,
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
            $material = Material::where('material_code', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($material as $items) {
            $response[] = array(
                "id" => $items->material_code,
                "text" => $items->material_code . ' - ' . $items->material_name
            );
        }

        return response()->json($response);
    }

    public function material_keyword(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $material = Material::limit(10)
                ->where('material_code', '!=', $request->produk)
                ->where('is_active', 't')
                ->get();
        } else {
            $material = Material::where('material_code', '!=', $request->produk)
                ->where(function ($query) use ($search) {
                    $query->where('material_code', 'ilike', '%' . $search . '%')
                        ->orWhere('material_name', 'ilike', '%' . $search . '%');
                })
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($material as $items) {
            $response[] = array(
                "id" => $items->material_code,
                "text" => $items->material_code . ' - ' . $items->material_name
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

    public function version(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $asumsi = Version_Asumsi::limit(10)
                ->get();
        } else {
            $asumsi = Version_Asumsi::where('version', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($asumsi as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->version
            );
        }

        return response()->json($response);
    }

    public function version_detail(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $asumsi = Asumsi_Umum::where('version_id', $request->version)
                ->limit(10)
                ->get();
        } else {
            $asumsi = Asumsi_Umum::where('version_id', $request->version)
                ->where('month_year', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($asumsi as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => format_month($items->month_year, 'se')
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

        $asumsi = DB::table('kuantiti_ren_daan')
            ->where('month_year', '=', $data)
            ->where('version_id', '=', $request->id)
            ->first();

        if ($asumsi == null) {
            return response()->json(['Code' => 200, 'data_kurs' => '']);
        } else {

            return response()->json(['Code' => 200, 'data_kurs' => $asumsi->usd_rate]);
        }
    }

    //  Datatable
    public function version_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $asumsi = Version_Asumsi::limit(10)
                ->get();
        } else {
            $asumsi = Version_Asumsi::where('version', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($asumsi as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->version
            );
        }
        return response()->json($response);
    }

    public function material_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $material = Material::limit(10)
                ->where('is_active', 't')
                ->get();
        } else {
            $material = Material::where('material_code', 'ilike', '%' . $search . '%')
                ->orWhere('material_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($material as $items) {
            $response[] = array(
                "id" => $items->material_code,
                "text" => $items->material_code . ' - ' . $items->material_name
            );
        }

        return response()->json($response);
    }

    public function plant_dt(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $plant = Plant::limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $plant = Plant::where('plant_code', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($plant as $items) {
            $response[] = array(
                "id" => $items->plant_code,
                "text" => $items->plant_code
            );
        }

        return response()->json($response);
    }

    public function kategori_material_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $kat_material = KategoriMaterial::limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $kat_material = KategoriMaterial::where('kategori_material_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($kat_material as $items) {
            $response[] = array(
                "id" => $items->kategori_material_name,
                "text" => $items->kategori_material_name
            );
        }

        return response()->json($response);
    }

    public function group_account_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $group_acc = GroupAccount::limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_acc = GroupAccount::where('group_account_code', 'ilike', '%' . $search . '%')
                ->orWhere('group_account_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($group_acc as $items) {
            $response[] = array(
                "id" => $items->group_account_code,
                "text" => $items->group_account_code . ' - ' . $items->group_account_desc
            );
        }

        return response()->json($response);
    }

    public function group_account_fc_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $group_acc = GroupAccountFC::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_acc = GroupAccountFC::where('group_account_fc', 'ilike', '%' . $search . '%')
                ->orWhere('group_account_fc_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($group_acc as $items) {
            $response[] = array(
                "id" => $items->group_account_fc,
                "text" => $items->group_account_fc . ' - ' . $items->group_account_fc_desc
            );
        }

        return response()->json($response);
    }
}
