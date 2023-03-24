<?php

namespace App\Http\Controllers;

use App\Models\Asumsi_Umum;
use App\Models\Company;
use App\Models\CostCenter;
use App\Models\Feature;
use App\Models\GLAccountFC;
use App\Models\GLAccount;
use App\Models\GLosCC;
use App\Models\GroupAccount;
use App\Models\GroupAccountFC;
use App\Models\KategoriBalans;
use App\Models\KategoriMaterial;
use App\Models\KategoriProduk;
use App\Models\Kurs;
use App\Models\Material;
use App\Models\Plant;
use App\Models\Regions;
use App\Models\Role;
use App\Models\Saldo_Awal;
use App\Models\Salr;
use App\Models\User;
use App\Models\Version_Asumsi;
use App\Models\Zco;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;

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
                ->orWhere('plant_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        foreach ($plant as $items) {
            $response[] = array(
                "id" => $items->plant_code,
                "text" => $items->plant_code . ' - ' . $items->plant_desc
            );
        }

        return response()->json($response);
    }

    public function plant_balans(Request $request)
    {
        $search = $request->search;
        $kategori = $request->kategori;
        $material = $request->material;

        if ($search == '') {
            if ($kategori == '1') {
                $plant = Saldo_Awal::select('saldo_awal.plant_code', 'plant.plant_desc')
                    ->leftjoin('plant', 'plant.plant_code', '=', 'saldo_awal.plant_code')
                    ->where('saldo_awal.material_code', $material)
                    ->whereNull('saldo_awal.deleted_at')
                    ->groupBy('saldo_awal.plant_code', 'plant.plant_desc')
                    ->get();
            } else {
                $plant = Plant::limit(10)
                    ->where('is_active', 't')
                    ->whereNull('deleted_at')
                    ->get();
            }
        } else {
            if ($kategori == '1') {
                $plant = Saldo_Awal::select('saldo_awal.plant_code', 'plant.plant_desc')
                    ->leftjoin('plant', 'plant.plant_code', '=', 'saldo_awal.plant_code')
                    ->where('saldo_awal.material_code', $material)
                    ->whereNull('saldo_awal.deleted_at')
                    ->where(function ($query) use ($search) {
                        $query->where('saldo_awal.plant_code', 'ilike', '%' . $search . '%')
                            ->orWhere('plant.plant_desc', 'ilike', '%' . $search . '%');
                    })
                    ->groupBy('saldo_awal.plant_code', 'plant.plant_desc')
                    ->get();

                dd($plant);
            } else {
                $plant = Plant::limit(10)
                    ->where('is_active', 't')
                    ->whereNull('deleted_at')
                    ->get();
            }
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'All'
        );
        foreach ($plant as $items) {
            $response[] = array(
                "id" => $items->plant_code . ' - ' . $items->plant_desc,
                "text" => $items->plant_code . ' - ' . $items->plant_desc
            );
        }

        return response()->json($response);
    }

    public function glos_cc_balans(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $glos_cc = GLosCC::limit(10)
                ->select('glos_cc.cost_center', 'cost_center.cost_center_desc', 'cons_rate.product_code')
                ->leftjoin('cost_center', 'glos_cc.cost_center', '=', 'cost_center.cost_center')
                ->rightjoin('cons_rate', 'cons_rate.product_code', '=', 'glos_cc.material_code')
                ->where('glos_cc.company_code', auth()->user()->company_code)
                ->whereNull('glos_cc.deleted_at')
                ->groupBy('glos_cc.cost_center', 'cost_center.cost_center_desc', 'cons_rate.product_code')
                ->get();
        } else {
            $glos_cc = GLosCC::limit(10)
                ->select('glos_cc.cost_center', 'cost_center.cost_center_desc', 'cons_rate.product_code')
                ->leftjoin('cost_center', 'glos_cc.cost_center', '=', 'cost_center.cost_center')
                ->rightjoin('cons_rate', 'cons_rate.product_code', '=', 'glos_cc.material_code')
                ->where('glos_cc.company_code', auth()->user()->company_code)
                ->whereNull('glos_cc.deleted_at')
                ->where('cost_center.cost_center', 'ilike', '%' . $search . '%')
                ->orWhere('cost_center.cost_center_desc', 'ilike', '%' . $search . '%')
                ->orWhere('glos_cc.material_code', 'ilike', '%' . $search . '%')
                ->groupBy('glos_cc.cost_center', 'cost_center.cost_center_desc', 'cons_rate.product_code')
                ->get();
        }

        $response = array();
        foreach ($glos_cc as $items) {
            $response[] = array(
                "id" => $items->cost_center . ' - ' . $items->cost_center_desc . ' - ' . $items->product_code,
                "text" => $items->cost_center . ' - ' . $items->cost_center_desc . ' ( ' . $items->product_code . ' )'
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

    public function kategori_produk(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $kategori_produk = KategoriProduk::limit(10)
                ->where('is_active', 't')
                ->get();
        } else {
            $kategori_produk = KategoriProduk::where('kategori_produk_name', 'ilike', '%' . $search . '%')
                ->orWhere('kategori_produk_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($kategori_produk as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->kategori_produk_name . ' - ' . $items->kategori_produk_desc
            );
        }

        return response()->json($response);
    }

    public function kategori_balans(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $kategori_balans = KategoriBalans::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $kategori_balans = KategoriBalans::where('kategori_balans', 'ilike', '%' . $search . '%')
                ->orWhere('kategori_balans_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        foreach ($kategori_balans as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->kategori_balans . ' - ' . $items->kategori_balans_desc
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

    public function general_ledger_fc(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $group_account = GLAccountFC::limit(10)
                ->where('group_account_fc', '=', $request->group_account)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_account = GLAccountFC::where('group_account_fc', '=', $request->group_account)
                ->where(function ($query) use ($search, $request) {
                    $query->where('gl_account_fc', 'ilike', '%' . $search . '%')
                        ->orWhere('gl_account_fc_desc', 'ilike', '%' . $search . '%');
                })
                ->whereNull('deleted_at')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($group_account as $items) {
            $response[] = array(
                "id" => $items->gl_account_fc,
                "text" => $items->gl_account_fc . ' ' . $items->gl_account_fc_desc,
            );
        }

        return response()->json($response);
    }

    public function general_ledger_fc_detail(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $group_account = GLAccountFC::limit(10)
                ->where('group_account_fc', '=', $request->group_account)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_account = GLAccountFC::where('group_account_fc', '=', $request->group_account)
                ->where(function ($query) use ($search, $request) {
                    $query->where('gl_account_fc', 'ilike', '%' . $search . '%')
                        ->orWhere('gl_account_fc_desc', 'ilike', '%' . $search . '%');
                })
                ->whereNull('deleted_at')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($group_account as $items) {
            $response[] = array(
                "id" => $items->gl_account_fc,
                "text" => $items->gl_account_fc . ' ' . $items->gl_account_fc_desc,
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
                ->orwhere('material_name', 'ilike', '%' . $search . '%')
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

    public function material_balans(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $material = Material::limit(10)
                ->where([
                    'is_active' => 't',
                    'kategori_material_id' => 1
                ])
                ->get();
        } else {
            $material = Material::where('material_code', 'ilike', '%' . $search . '%')
                ->orWhere('material_name', 'ilike', '%' . $search . '%')
                ->where([
                    'is_active' => 't',
                    'kategori_material_id' => 1
                ])
                ->limit(10)
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
                ->where('is_active', 't')
                ->get();
        } else {
            $material = Material::where(function ($query) use ($search) {
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
            $region = Regions::where('region_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->where('is_active', 't')
                ->get();
        }

        $response = array();
        foreach ($region as $items) {
            $response[] = array(
                "id" => $items->region_name,
                "text" => $items->region_desc
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

    public function version_inflasi(Request $request)
    {
        $search = $request->search;
        $timestamp = explode('-', $request->periode);
        if ($search == '') {
            $asumsi = Asumsi_Umum::select('asumsi_umum.*', 'version_asumsi.version')
                ->where('asumsi_umum.month_year', 'ilike', '%' . $timestamp[1] . '-' . $timestamp[0] . '-01' . '%')
                ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'asumsi_umum.version_id')
                ->limit(10)
                ->get();
        } else {
            $asumsi = Asumsi_Umum::select('asumsi_umum.*', 'version_asumsi.version')
                ->where('version_asumsi.version', 'ilike', '%' . $search . '%')
                ->where('asumsi_umum.month_year', 'ilike', '%' . $timestamp[1] . '-' . $timestamp[0] . '-01' . '%')
                ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'asumsi_umum.version_id')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($asumsi as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->version . ' - ' . $items->inflasi
            );
        }

        return response()->json($response);
    }

    public function cost_center(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $cost_center = CostCenter::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $cost_center = CostCenter::where('cost_center', 'ilike', '%' . $search . '%')
                ->orWhere('cost_center_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        foreach ($cost_center as $items) {
            $response[] = array(
                "id" => $items->cost_center,
                "text" => $items->cost_center . ' - ' . $items->cost_center_desc,
            );
        }

        return response()->json($response);
    }

    public function cost_center_salr(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $cost_center = Salr::select('salrs.cost_center', 'cost_center.cost_center_desc')
                ->leftjoin('cost_center', 'salrs.cost_center', '=', 'cost_center.cost_center')
                ->whereNull('salrs.deleted_at')
                ->groupBy('salrs.cost_center', 'cost_center.cost_center_desc')
                ->limit(10)
                ->get();
        } else {
            $cost_center = Salr::select('salrs.cost_center', 'cost_center.cost_center_desc')
                ->leftjoin('cost_center', 'salrs.cost_center', '=', 'cost_center.cost_center')
                ->where('salrs.cost_center', 'ilike', '%' . $search . '%')
                ->orWhere('cost_center.cost_center_desc', 'ilike', '%' . $search . '%')
                ->whereNull('salrs.deleted_at')
                ->groupBy('salrs.cost_center', 'cost_center.cost_center_desc')
                ->limit(10)
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($cost_center as $items) {
            $response[] = array(
                "id" => $items->cost_center,
                "text" => $items->cost_center . ' - ' . $items->cost_center_desc,
            );
        }

        return response()->json($response);
    }

    public function cost_element(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $cost_center = GLAccount::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $cost_center = GLAccount::where('gl_account', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        foreach ($cost_center as $items) {
            $response[] = array(
                "id" => $items->gl_account,
                "text" => $items->gl_account . ' ' . $items->gl_account_desc,
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

        if (strlen($data[0]) == 1) {
            $date = $data[1] . '-0' . $data[0] . '-01';
        } else {
            $date = $data[1] . '-' . $data[0] . '-01';
        }

        $kurs = DB::table('kurs')
            ->where('month_year', '=', $date)
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

    public function check_kursv2(Request $request)
    {
        //        $data = explode('-', $request->periode);

        $kurs = DB::table('kurs')
            ->where('month_year', 'ilike', '%' . $request->periode . '%')
            ->first();


        //        dd($kurs, $request->periode);
        if ($kurs == null) {
            return response()->json(['Code' => 200, 'data_kurs' => '']);
        } else {
            return response()->json(['Code' => 200, 'data_kurs' => $kurs->usd_rate]);
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

    public function material_balans_dt(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $material = Material::limit(10)
                ->where([
                    'is_active' => 't',
                    'kategori_material_id' => 1
                ])
                ->get();
        } else {
            $material = Material::where('material_code', 'ilike', '%' . $search . '%')
                ->orWhere('material_name', 'ilike', '%' . $search . '%')
                ->where([
                    'is_active' => 't',
                    'kategori_material_id' => 1
                ])
                ->limit(10)
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
                "text" => $items->plant_code . ' ' . $items->plant_desc
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

    public function kategori_produk_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $kat_produk = KategoriProduk::limit(10)
                ->where('is_active', 't')
                ->whereNull('deleted_at')
                ->get();
        } else {
            $kat_produk = KategoriProduk::where('kategori_produk_name', 'ilike', '%' . $search . '%')
                ->orWhere('kategori_produk_desc', 'ilike', '%' . $search . '%')
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
        foreach ($kat_produk as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->kategori_produk_name . ' - ' . $items->kategori_produk_desc
            );
        }

        return response()->json($response);
    }

    public function kategori_balans_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $kat_balans = KategoriBalans::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $kat_balans = KategoriBalans::where('kategori_balans', 'ilike', '%' . $search . '%')
                ->orWhere('kategori_balans_desc', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->whereNull('deleted_at')
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($kat_balans as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->kategori_balans . ' - ' . $items->kategori_balans_desc
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

    public function gl_account_fc_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $group_acc = GLAccountFC::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_acc = GLAccountFC::where('gl_account_fc', 'ilike', '%' . $search . '%')
                ->orWhere('gl_account_fc_desc', 'ilike', '%' . $search . '%')
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
                "text" => $items->group_account_fc . ' - ' . $items->gl_account_fc_desc
            );
        }

        return response()->json($response);
    }

    public function cost_center_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $group_acc = CostCenter::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_acc = CostCenter::where('cost_center', 'ilike', '%' . $search . '%')
                ->orWhere('cost_center_desc', 'ilike', '%' . $search . '%')
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
                "id" => $items->cost_center,
                "text" => $items->cost_center . ' - ' . $items->cost_center_desc
            );
        }

        return response()->json($response);
    }

    public function cost_element_dt(Request $request)
    {
        $search = $request->search;
        if ($search == 'all') {
            $group_acc = GLAccount::limit(10)
                ->whereNull('deleted_at')
                ->get();
        } else {
            $group_acc = GLAccount::where('gl_account', 'ilike', '%' . $search . '%')
                ->orWhere('gl_account_desc', 'ilike', '%' . $search . '%')
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
                "id" => $items->gl_account,
                "text" => $items->gl_account . ' - ' . $items->gl_account_desc
            );
        }

        return response()->json($response);
    }

    public function zco_product_dt(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $zco_product = Zco::select('zco.product_code', 'material.material_name')
                ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
                ->whereNull('zco.deleted_at')
                ->groupBy('zco.product_code', 'material.material_name')
                ->limit(10)
                ->get();
        } else {
            $zco_product = Salr::select('zco.product_code', 'material.material_name')
                ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
                ->where('zco.product_code', 'ilike', '%' . $search . '%')
                ->orWhere('material.material_name', 'ilike', '%' . $search . '%')
                ->whereNull('zco.deleted_at')
                ->groupBy('zco.product_code', 'material.material_name')
                ->limit(10)
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($zco_product as $items) {
            $response[] = array(
                "id" => $items->product_code,
                "text" => $items->product_code . ' - ' . $items->material_name,
            );
        }

        return response()->json($response);
    }

    public function zco_plant_dt(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $zco_product = Zco::select('zco.plant_code', 'plant.plant_desc')
                ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
                ->where('product_code', $request->product)
                ->whereNull('zco.deleted_at')
                ->groupBy('zco.plant_code', 'plant.plant_desc')
                ->limit(10)
                ->get();
        } else {
            $zco_product = Salr::select('zco.plant_code', 'plant.plant_desc')
                ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
                ->where('product_code', $request->product)
                ->where('zco.plant_code', 'ilike', '%' . $search . '%')
                ->orWhere('plant.plant_desc', 'ilike', '%' . $search . '%')
                ->whereNull('zco.deleted_at')
                ->groupBy('zco.plant_code', 'plant.plant_desc')
                ->limit(10)
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua'
        );
        foreach ($zco_product as $items) {
            $response[] = array(
                "id" => $items->plant_code,
                "text" => $items->plant_code . ' - ' . $items->plant_desc,
            );
        }

        return response()->json($response);
    }

    public function company(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            if (auth()->user()->company_code == 'A000') {
                $company = Company::limit(10)
                    ->where('company_code', '!=', 'A000')
                    ->get();
            } else {
                $company = Company::limit(10)
                    ->where('company_code', auth()->user()->company_code)
                    ->get();
            }
        } else {

            if (auth()->user()->company_code == 'A000') {
                $company = Company::limit(10)
                    ->where('company_code', '!=', 'A000')
                    ->where('company_code_pi', function ($query) use ($search) {
                        $query->where('company_code', 'ilike', '%' . $search . '%')
                            ->orWhere('company_name', 'ilike', '%' . $search . '%');
                    })
                    ->get();
            } else {
                $company = Company::limit(10)
                    ->where('company_code', auth()->user()->company_code)
                    ->get();
            }
        }

        $response = array();

        foreach ($company as $items) {
            $response[] = array(
                "id" => $items->company_code,
                "text" => $items->company_code . ' - ' . $items->company_name
            );
        }

        return response()->json($response);
    }


    public function company_filter(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            if (auth()->user()->company_code == 'A000') {
                $company = Company::limit(10)
                    ->where('company_code', '!=', 'A000')
                    ->get();
            } else {
                $company = Company::limit(10)
                    ->where('company_code', auth()->user()->company_code)
                    ->get();
            }
        } else {
            if (auth()->user()->company_code == 'A000') {
                $company = Company::limit(10)
                    ->where('company_code', '!=', 'A000')
                    ->where('company_code_pi', function ($query) use ($search) {
                        $query->where('company_code', 'ilike', '%' . $search . '%')
                            ->orWhere('company_name', 'ilike', '%' . $search . '%');
                    })
                    ->get();
            } else {
                $company = Company::limit(10)
                    ->where('company_code', auth()->user()->company_code)
                    ->get();
            }
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua Perusahaan'
        );

        foreach ($company as $items) {
            $response[] = array(
                "id" => $items->company_code,
                "text" => $items->company_code . ' - ' . $items->company_name
            );
        }

        return response()->json($response);
    }

    public function main_company_filter(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $company = Company::limit(10)
                ->get();
        } else {
            $company = Company::where('company_code', 'ilike', '%' . $search . '%')
                ->orWhere('company_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        $response[] = array(
            "id" => 'all',
            "text" => 'Semua Perusahaan'
        );

        foreach ($company as $items) {
            $response[] = array(
                "id" => $items->company_code,
                "text" => $items->company_code . ' - ' . $items->company_name
            );
        }

        return response()->json($response);
    }

    public function permission(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $permission = Permission::limit(10)
                ->get();
        } else {
            $permission = Permission::where('name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($permission as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->name,
            );
        }

        return response()->json($response);
    }

    public function role_spatie(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $role = SpatieRole::limit(10)
                ->get();
        } else {
            $role = SpatieRole::where('name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($role as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->name,
            );
        }

        return response()->json($response);
    }

    public function user(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $usr = User::limit(10)
                ->get();
        } else {
            $usr = User::where('name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($usr as $items) {
            $response[] = array(
                "id" => $items->id,
                "text" => $items->name
            );
        }

        return response()->json($response);
    }

    public function menu(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $feat = Feature::limit(10)
                ->get();
        } else {
            $feat = Feature::where('feature_name', 'ilike', '%' . $search . '%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach ($feat as $items) {
            $response[] = array(
                "id" => $items->kode_unik,
                "text" => $items->feature_name
            );
        }

        return response()->json($response);
    }
}
