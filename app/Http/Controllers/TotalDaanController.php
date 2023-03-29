<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_TotalDaanDataTable;
use App\DataTables\Master\TotalDaanDataTable;
use App\Exports\Horizontal\TotalDaanExport;
use App\Models\TotalDaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TotalDaanController extends Controller
{
    public function index(Request $request, TotalDaanDataTable $totaldaanDataTable, H_TotalDaanDataTable $h_TotalDaanDataTable)
    {
        if ($request->data == 'index') {
            if ($request->currency) {
                return $totaldaanDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version, 'currency' => $request->currency])->render('pages.buku_besar.total_daan.index');
            } else {
                return $totaldaanDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version, 'currency' => 'Rupiah'])->render('pages.buku_besar.total_daan.index');
            }
        } elseif ($request->data == 'horizontal') {
            if ($request->currency) {
                return $h_TotalDaanDataTable->with(['company' => $request->company, 'version' => $request->version, 'val' => $request->val, 'currency' => $request->currency])->render('pages.buku_besar.total_daan.index');
            } else {
                return $h_TotalDaanDataTable->with(['company' => $request->company, 'version' => $request->version, 'val' => $request->val, 'currency' => 'Rupiah'])->render('pages.buku_besar.total_daan.index');
            }
        } elseif ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();
            return response()->json(['code' => 200, 'asumsi' => $asumsi]);
        }
        return view('pages.buku_besar.total_daan.index');
    }

    public function export_horizontal(Request $request)
    {
        $cc = auth()->user()->company_code;
        $mata_uang = $request->mata_uang;
        $value_filter = $request->value;

        $query = DB::table('qty_rendaan')
            ->select(DB::Raw("CONCAT(qty_rendaan.material_code, ' - ', material.material_name) mat"), 'regions.region_desc', 'asumsi_umum.month_year', 'asumsi_umum.usd_rate', DB::Raw("COALESCE(qty_rendaan.qty_rendaan_value * (price_rendaan.price_rendaan_value * (1 + (asumsi_umum.adjustment / 100))), -1) qty"))
            ->leftjoin('material', 'material.material_code', '=', 'qty_rendaan.material_code')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'qty_rendaan.version_id')
            ->leftjoin('regions', 'regions.region_name', '=', 'qty_rendaan.region_name')
            ->leftJoin('price_rendaan', function ($join) {
                $join->on('qty_rendaan.material_code', '=', 'price_rendaan.material_code');
                $join->on('qty_rendaan.region_name', '=', 'price_rendaan.region_name');
                $join->on('qty_rendaan.version_id', '=', 'price_rendaan.version_id');
                $join->on('qty_rendaan.asumsi_umum_id', '=', 'price_rendaan.asumsi_umum_id');
                $join->on('qty_rendaan.company_code', '=', 'price_rendaan.company_code')
                    ->whereNull('price_rendaan.deleted_at');
            })
            ->whereNull('qty_rendaan.deleted_at')
            ->where('qty_rendaan.version_id', $request->version)
            // ->where('qty.company_code', $cc)
            ->orderBy('mat')
            ->orderBy('month_year')
            ->orderBy('region_desc')
            ->get()
            ->toArray();

        if ($mata_uang == 'USD') {
            $query = array_map(function ($val) {
                if ($val->qty != -1) {
                    $val->qty = round($val->qty / $val->usd_rate, 2);
                }
                return $val;
            }, $query);
        }

        if ($value_filter == 1) {
            $query = array_filter($query, function ($val) {
                return $val->qty != -1;
            });
        } elseif ($value_filter == 2) {
            $query = array_filter($query, function ($val) {
                return $val->qty == -1;
            });
        }

        // return response()->json($query);

        $data = array_reduce($query, function ($acc, $curr) {
            if (property_exists($acc, $curr->mat)) {
                if (property_exists($acc->{$curr->mat}, $curr->region_desc)) {
                    array_push($acc->{$curr->mat}->{$curr->region_desc}, $curr->qty);
                } else {
                    $acc->{$curr->mat}->{$curr->region_desc} = [$curr->qty];
                }
            } else {
                $acc->{$curr->mat} = (object)[$curr->region_desc => [$curr->qty]];
            }

            return $acc;
        }, (object)[]);

        // return response()->json($data);



        $header = ['MATERIAL', 'REGION', ...array_unique(array_map(function ($v) {
            return $v->month_year;
        }, $query))];


        $body = array_reduce(array_map(function ($v) use ($data) {
            $_arrays = [];

            foreach ($data->{$v} as $w => $x) {
                $_arr = [$v, $w];

                foreach ($x as $y) {
                    array_push($_arr, $y);
                }

                array_push($_arrays, $_arr);
            }
            return $_arrays;
        }, array_keys(get_object_vars($data))), function ($acc, $curr) {
            return array_merge($acc, $curr);
        }, []);

        // return response()->json($body);

        $data = [
            'header' => $header,
            'body'   => $body,
            'mata_uang' => $mata_uang
        ];

        return Excel::download(new TotalDaanExport($data), "Total Pengadaan - Horizontal.xlsx");
    }
}
