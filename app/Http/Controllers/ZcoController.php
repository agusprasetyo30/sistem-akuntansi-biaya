<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_ZcoDataTable;
use App\DataTables\Master\H_ZcoGroupAccountDataTable;
use App\DataTables\Master\ZcoDataTable;
use App\Exports\MultipleSheet\MS_ZcoExport;
use App\Exports\Horizontal\ZCOExport;
use App\Exports\Horizontal\ZCOGroupAccountExport;
use App\Imports\ZcoImport;
use App\Models\Asumsi_Umum;
use App\Models\GLAccount;
use App\Models\GroupAccount;
use App\Models\Material;
use App\Models\Plant;
use App\Models\Zco;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ZcoController extends Controller
{
    public function index(Request $request, ZcoDataTable $zcoDataTable, H_ZcoDataTable $h_zcoDataTable, H_ZcoGroupAccountDataTable $h_zcogroupaccountDataTable)
    {
        if ($request->data == 'index') {
            return $zcoDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.zco.index');
        } else if ($request->data == 'horizontal') {
            $asumsi = Asumsi_Umum::where('id',  $request->moth)->first();
            return $h_zcoDataTable->with([
                'material' => $request->material,
                'plant' => $request->plant,
                'format' => $request->format_data,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'moth' => $asumsi->month_year ?? null,
                'version' => $request->version,
                'company' => $request->company,
            ])->render('pages.buku_besar.zco.index');
        } else if ($request->data == 'horizontal_group_account') {
            $asumsi = Asumsi_Umum::where('id',  $request->moth)->first();
            return $h_zcogroupaccountDataTable->with([
                'material' => $request->material,
                'plant' => $request->plant,
                'format' => $request->format_data,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'moth' => $asumsi->month_year ?? null,
                'version' => $request->version,
                'company' => $request->company,
            ])->render('pages.buku_besar.zco.index');
        } else if ($request->data == 'material') {
            if (auth()->user()->mapping_akses('zco')->company_code == 'all') {
                $validator = Validator::make($request->all(), [
                    "version" => 'required',
                    "company" => 'required',
                ], validatorMsg());

                if ($validator->fails())
                    return $this->makeValidMsg($validator);
            } else {
                $validator = Validator::make($request->all(), [
                    "version" => 'required',
                ], validatorMsg());

                if ($validator->fails())
                    return $this->makeValidMsg($validator);
            }

            $material = Zco::select('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name')
                ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
                ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
                ->groupBy('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name');

            if ($request->material != 'all') {
                $material->where('zco.product_code', $request->material);
            }

            if ($request->plant != 'all') {
                $material->where('zco.plant_code', $request->plant);
            }

            if ($request->version) {
                $material->where('zco.version_id', $request->version);
            }

            if ($request->company) {
                $material->where('zco.company_code', $request->company);
            }

            $asumsi = Asumsi_Umum::where('id',  $request->moth)->first();
            if ($request->format_data == '0') {
                $material->where('periode', 'ilike', '%' . $asumsi->month_year . '%');
            } else if ($request->format_data == '1') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $material->whereBetween('periode', [$start_date, $end_date]);
            }
            // dd($request->material, $request->plant);
            $material = $material->get();
            // dd($material);
            return response()->json(['code' => 200, 'material' => $material]);
        } else if ($request->data == 'group_account') {
            if (auth()->user()->mapping_akses('zco')->company_code == 'all') {
                $validator = Validator::make($request->all(), [
                    "version" => 'required',
                    "company" => 'required',
                ], validatorMsg());

                if ($validator->fails())
                    return $this->makeValidMsg($validator);
            } else {
                $validator = Validator::make($request->all(), [
                    "version" => 'required',
                ], validatorMsg());

                if ($validator->fails())
                    return $this->makeValidMsg($validator);
            }

            $group_account = Zco::select('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name')
                ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
                ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
                ->groupBy('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name');

            if ($request->material != 'all') {
                $group_account->where('zco.product_code', $request->material);
            }

            if ($request->plant != 'all') {
                $group_account->where('zco.plant_code', $request->plant);
            }

            if ($request->version) {
                $group_account->where('zco.version_id', $request->version);
            }

            if ($request->company) {
                $group_account->where('zco.company_code', $request->company);
            }

            $asumsi = Asumsi_Umum::where('id',  $request->moth)->first();
            if ($request->format_data == '0') {
                $group_account->where('periode', 'ilike', '%' . $asumsi->month_year . '%');
            } else if ($request->format_data == '1') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $group_account->whereBetween('periode', [$start_date, $end_date]);
            }
            // dd($request->material, $request->plant);
            $group_account = $group_account->get();

            return response()->json(['code' => 200, 'group_account' => $group_account]);
        }

        return view('pages.buku_besar.zco.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "plant_code" => 'required',
                "product_code" => 'required',
                "material_code" => 'required',
                "cost_element" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)->first();

            $input['company_code'] = auth()->user()->company_code;
            $input['version_id'] = (int) $request->version;
            $input['periode'] = $data_asumsi->month_year;
            $input['plant_code'] = $request->plant_code;
            $input['product_code'] = $request->product_code;
            $input['product_qty'] = $request->product_qty;
            $input['cost_element'] = $request->cost_element;
            $input['material_code'] = $request->material_code;
            $input['total_qty'] = $request->total_qty;
            $input['currency'] = $request->currency;
            $input['total_amount'] = $request->total_amount;
            $input['unit_price_product'] = $request->unit_price_product;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Zco::create($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "plant_code" => 'required',
                "product_code" => 'required',
                "material_code" => 'required',
                "cost_element" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)->first();

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['version_id'] = (int) $request->version;
            $input['periode'] = $data_asumsi->month_year;
            $input['product_code'] = $request->product_code;
            $input['product_qty'] = $request->product_qty;
            $input['cost_element'] = $request->cost_element;
            $input['material_code'] = $request->material_code;
            $input['total_qty'] = $request->total_qty;
            $input['currency'] = $request->currency;
            $input['total_amount'] = $request->total_amount;
            $input['unit_price_product'] = $request->unit_price_product;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Zco::where('id', $request->id)
                ->update($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Zco::where('id', $request->id)
                ->delete();

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $transaction = DB::transaction(function () use ($request) {
                $asumsi = Asumsi_Umum::where('id', $request->detail_version_import)->first();
                $empty_excel = Excel::toArray(new ZcoImport($asumsi), $request->file('file'));
                if ($empty_excel[0]) {
                    $file = $request->file('file')->store('import');
                    Zco::where('periode', 'ilike', '%' . $asumsi->month_year . '%')->delete();
                    $import = new ZcoImport($asumsi);
                    $import->import($file);

                    $data_fail = $import->failures();
                } else {
                    $data_fail = [];
                }
                return $data_fail;
            });

            if ($transaction->isNotEmpty()) {
                return setResponse([
                    'code' => 500,
                    'title' => 'Gagal meng-import data',
                ]);
            } else {
                return setResponse([
                    'code' => 200,
                    'title' => 'Berhasil meng-import data'
                ]);
            }
        } catch (\Exception $exception) {
            $asumsi = Asumsi_Umum::where('id', $request->detail_version_import)->first();
            $empty_excel = Excel::toArray(new ZcoImport($asumsi), $request->file('file'));

            $plant = [];
            $plant_ = [];
            $product = [];
            $product_ = [];
            $cost_element = [];
            $cost_element_ = [];
            $material = [];
            $material_ = [];

            foreach ($empty_excel[0] as $key => $value) {
                array_push($plant, 'plant ' . $value['plant_code'] . ' tidak ada pada master');
                $d_plant = Plant::whereIn('plant_code', [$value['plant_code']])->first();
                if ($d_plant) {
                    array_push($plant_, 'plant ' . $d_plant->plant_code . ' tidak ada pada master');
                }

                array_push($product, 'produk ' . $value['product_code'] . ' tidak ada pada master');
                $d_product = Material::whereIn('material_code', [$value['product_code']])->first();
                if ($d_product) {
                    array_push($product_, 'produk ' . $d_product->material_code . ' tidak ada pada master');
                }

                array_push($cost_element, 'cost element ' . $value['cost_element'] . ' tidak ada pada master');
                $d_cost_element = GLAccount::whereIn('gl_account', [$value['cost_element']])->first();
                if ($d_cost_element) {
                    array_push($cost_element_, 'cost element ' . $d_cost_element->gl_account . ' tidak ada pada master');
                }

                array_push($material, 'material ' . $value['material_code'] . ' tidak ada pada master');
                $d_material = Material::whereIn('material_code', [$value['material_code']])->first();
                if ($d_material) {
                    array_push($material_, 'material ' . $d_material->material_code . ' tidak ada pada master');
                }
            }

            $result_plant = array_diff($plant, $plant_);
            $result_product = array_diff($product, $product_);
            $result_cost_element = array_diff($cost_element, $cost_element_);
            $result_material = array_diff($material, $material_);
            $result = array_merge($result_plant, $result_product, $result_cost_element, $result_material);
            $res = array_unique($result);

            if ($res) {
                $msg = '';

                foreach ($res as $message)
                    $msg .= '<p>' . $message . '</p>';

                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => $msg
                ]);
            } else {
                return setResponse([
                    'code' => 400,
                ]);
            }
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new MS_ZcoExport(), 'zco.xlsx');
    }

    public function check(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "periode" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        try {
            $asumsi = Asumsi_Umum::where('id', $request->periode)->first();

            $check = Zco::where('periode', 'ilike', '%' . $asumsi->month_year . '%')
                ->first();

            if ($check == null) {
                return setResponse([
                    'code' => 200,
                ]);
            } else {
                return setResponse([
                    'code' => 201,
                    'title' => 'Apakah anda yakin?',
                    'message' => 'Data Pada Periode Ini Telah Ada, Yakin Untuk Mengganti ?'
                ]);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function exportHorizontal(Request $request)
    {
        // Material list by user company code
        $material_list = Material::where('company_code', auth()->user()->company_code)
            ->orderBy('material_code', 'asc')
            ->get();


        // Product list for header dinamis
        $product_list = Zco::select('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name', 'material.material_code')
            ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
            ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
            ->groupBy('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name', 'material.material_code')
            ->orderBy('material.material_code', 'asc')
            ->get();

        // return response()->json($product_list);


        if ($request->material != 'all') {
            $product_list = $product_list->where('product_code',  $request->material);
        }

        if ($request->plant != 'all') {
            $product_list = $product_list->where('plant_code', $request->plant);
        }

        $temporary_value['harga_satuan'] = [];
        $temporary_value['cr'] = [];
        $temporary_value['biaya_per_ton'] = [];
        $temporary_value['total_biaya'] = [];

        // Dibuat variabel index temporary dikarenakan case nya ada index yang tidak diawali dengan 0
        $key_temp = 0;

        // Proses memasukan data berdasarkan rule/aturan
        foreach ($material_list as $query) {
            foreach ($product_list as $item) {
                array_push($temporary_value['harga_satuan'], ['key' => $key_temp, 'value' => $this->getHargaSatuanCount($request, $item, $query, 'horizontal')]);
                array_push($temporary_value['cr'], ['key' => $key_temp, 'value' => $this->getCRCount($request, $item, $query, 'horizontal')]);
                array_push($temporary_value['biaya_per_ton'], ['key' => $key_temp, 'value' => $this->getBiayaPerTon($request, $item, $query, 'horizontal')]);
                array_push($temporary_value['total_biaya'], ['key' => $key_temp, 'value' => $this->getTotalBiaya($request, $item, $query, 'horizontal')]);

                $key_temp++;
            }

            $key_temp = 0;
        }

        // Menghitung jumlah total asumsi umum sebagai acuan index
        $product_index_count = $product_list->count() - 1;

        // Memisahkan data array yang disesuaikan dengan array key & transaksi (p, q, <nila></nila>i)
        $fixed_value['harga_satuan'] = getSeparateValue($temporary_value['harga_satuan'], $product_index_count);
        $fixed_value['cr'] = getSeparateValue($temporary_value['cr'], $product_index_count);
        $fixed_value['biaya_per_ton'] = getSeparateValue($temporary_value['biaya_per_ton'], $product_index_count);
        $fixed_value['total_biaya'] = getSeparateValue($temporary_value['total_biaya'], $product_index_count);

        // Menghitung total dari kolom
        for ($i = 0; $i < $product_list->count(); $i++) {
            $total['harga_satuan'][$i] = array_sum($fixed_value['harga_satuan'][$i]);
            $total['cr'][$i] = array_sum($fixed_value['cr'][$i]);
            $total['biaya_per_ton'][$i] = array_sum($fixed_value['biaya_per_ton'][$i]);
            $total['total_biaya'][$i] = array_sum($fixed_value['total_biaya'][$i]);
        }

        $data = [
            'material_lists'   => $material_list,
            'product_lists'    => $product_list,
            'fixed_value_data' => $fixed_value,
            'total'            => $total
        ];

        return Excel::download(new ZCOExport($data), "ZCO Horizontal.xlsx");

        // return view('pages.buku_besar.zco.export_horizontal', $data);
    }

    public function exportGroupAccount(Request $request)
    {
        // Group Account by user company code
        $group_account_list = GroupAccount::where('company_code', auth()->user()->company_code)
            ->get();

        // Product list for header dinamis
        $product_list = Zco::select('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name')
            ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
            ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
            ->groupBy('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name')
            ->get();

        if ($request->material != 'all') {
            $product_list = $product_list->where('product_code',  $request->material);
        }

        if ($request->plant != 'all') {
            $product_list = $product_list->where('plant_code', $request->plant);
        }

        if ($request->format_data == '0') {
            $temp = explode('-', $request->moth);
            $timemonth = $temp[1] . '-' . $temp[0];

            $product_list = $product_list->where('periode', 'ilike', '%' . $timemonth . '%');
        } else if ($request->format_data == '1') {
            $start_temp = explode('-', $request->start_month);
            $end_temp = explode('-', $request->end_month);
            $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
            $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

            $product_list = $product_list->whereBetween('periode', [$start_date, $end_date]);
        }

        $temporary_value['harga_satuan'] = [];
        $temporary_value['cr'] = [];
        $temporary_value['biaya_per_ton'] = [];
        $temporary_value['total_biaya'] = [];

        // Dibuat variabel index temporary dikarenakan case nya ada index yang tidak diawali dengan 0
        $key_temp = 0;

        // Proses memasukan data berdasarkan rule/aturan
        foreach ($group_account_list as $query) {
            foreach ($product_list as $item) {
                array_push($temporary_value['harga_satuan'], ['key' => $key_temp, 'value' => $this->getHargaSatuanCount($request, $item, $query, 'group_account')]);
                array_push($temporary_value['cr'], ['key' => $key_temp, 'value' => $this->getCRCount($request, $item, $query, 'group_account')]);
                array_push($temporary_value['biaya_per_ton'], ['key' => $key_temp, 'value' => $this->getBiayaPerTon($request, $item, $query, 'group_account')]);
                array_push($temporary_value['total_biaya'], ['key' => $key_temp, 'value' => $this->getTotalBiaya($request, $item, $query, 'group_account')]);

                $key_temp++;
            }

            $key_temp = 0;
        }

        // Menghitung jumlah total asumsi umum sebagai acuan index
        $product_index_count = $product_list->count() - 1;

        // Memisahkan data array yang disesuaikan dengan array key & transaksi (p, q, <nila></nila>i)
        $fixed_value['harga_satuan'] = getSeparateValue($temporary_value['harga_satuan'], $product_index_count);
        $fixed_value['cr'] = getSeparateValue($temporary_value['cr'], $product_index_count);
        $fixed_value['biaya_per_ton'] = getSeparateValue($temporary_value['biaya_per_ton'], $product_index_count);
        $fixed_value['total_biaya'] = getSeparateValue($temporary_value['total_biaya'], $product_index_count);

        // Menghitung total dari kolom
        for ($i = 0; $i < $product_list->count(); $i++) {
            $total['harga_satuan'][$i] = array_sum($fixed_value['harga_satuan'][$i]);
            $total['cr'][$i] = array_sum($fixed_value['cr'][$i]);
            $total['biaya_per_ton'][$i] = array_sum($fixed_value['biaya_per_ton'][$i]);
            $total['total_biaya'][$i] = array_sum($fixed_value['total_biaya'][$i]);
        }

        $data = [
            'group_account_list'  => $group_account_list,
            'product_lists'       => $product_list,
            'fixed_value_data'    => $fixed_value,
            'total'            => $total
        ];

        // return view('pages.buku_besar.zco.export_group_account', $data);
        return Excel::download(new ZCOGroupAccountExport($data), "ZCO Group Account.xlsx");
    }

    /**
     * Fungsi yang digunakna untuk menghitung data Harga Satuan
     *
     * @param [type] $request
     * @param [type] $item
     * @param [type] $material_list
     * @return void
     */
    public function getHargaSatuanCount($request, $query_col_data, $query_row_data, $type)
    {
        if ($type == 'horizontal') {

            $total_qty = Zco::select(DB::raw('SUM(total_qty) as total_qty'))
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                    'material_code' => $query_row_data->material_code,
                ]);

            $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                    'material_code' => $query_row_data->material_code,
                ]);

            $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                ])->groupBy('product_qty', 'periode');

            if ($request->format_data == '0') {
                $temp = explode('-', $request->moth);
                $timemonth = $temp[1] . '-' . $temp[0];

                $total_qty->where('periode', 'ilike', '%' . $timemonth . '%');
                $total_biaya->where('periode', 'ilike', '%' . $timemonth . '%');
                $kuantum_produksi->where('periode', 'ilike', '%' . $timemonth . '%');
            } else if ($request->format_data == '1') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $total_qty->whereBetween('periode', [$start_date, $end_date]);
                $total_biaya->whereBetween('periode', [$start_date, $end_date]);
                $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
            }

            $total_qty = $total_qty->first();
            $total_biaya = $total_biaya->first();
            $kuantum_produksi = $kuantum_produksi->get()->toArray();

            $tot_kuanprod = 0;

            for ($i = 0; $i < count($kuantum_produksi); $i++) {
                $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
            }

            $biaya_perton = 0;
            if ($total_biaya->total_amount != 0 && $tot_kuanprod > 0) {
                $biaya_perton = $total_biaya->total_amount / $tot_kuanprod;
            }

            $cr = 0;
            if ($total_qty->total_qty != 0 && $tot_kuanprod > 0) {
                $cr = $total_qty->total_qty / $tot_kuanprod;
            }

            $harga_satuan = 0;
            if ($biaya_perton != 0 && $cr != 0) {
                $harga_satuan = $biaya_perton / $cr;
            }
        } else if ($type == 'group_account') {
            // TODO

            $harga_satuan = 0;
        }

        return $harga_satuan ? $harga_satuan : 0;
    }

    /**
     * Fungsi yang digunakna untuk menghitung data CR
     *
     * @param [type] $request
     * @param [type] $item
     * @param [type] $material_list
     * @return void
     */
    public function getCRCount($request, $query_col_data, $query_row_data, $type)
    {
        if ($type == 'horizontal') {
            $total_qty = Zco::select(DB::raw('SUM(total_qty) as total_qty'))
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                    'material_code' => $query_row_data->material_code,
                ]);


            $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                ])->groupBy('product_qty', 'periode');

            if ($request->format_data == '0') {
                $temp = explode('-', $request->moth);
                $timemonth = $temp[1] . '-' . $temp[0];

                $total_qty->where('periode', 'ilike', '%' . $timemonth . '%');
                $kuantum_produksi->where('periode', 'ilike', '%' . $timemonth . '%');
            } else if ($request->format_data == '1') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $total_qty->whereBetween('periode', [$start_date, $end_date]);
                $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
            }

            $total_qty = $total_qty->first();
            $kuantum_produksi = $kuantum_produksi->get()->toArray();

            $tot_kuanprod = 0;

            for ($i = 0; $i < count($kuantum_produksi); $i++) {
                $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
            }

            $cr = 0;
            if ($total_qty->total_qty != 0 && $tot_kuanprod > 0) {
                $cr = $total_qty->total_qty / $tot_kuanprod;
            }
        } else if ($type == 'group_account') {
            // TODO
            $cr = 0;
        }

        return $cr ? $cr : 0;
    }

    /**
     * Fungsi yang digunakna untuk menghitung data Biaya per ton
     *
     * @param [type] $request
     * @param [type] $item
     * @param [type] $material_list
     * @return void
     */
    public function getBiayaPerTon($request, $query_col_data, $query_row_data, $type)
    {
        if ($type == 'horizontal') {
            $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                    'material_code' => $query_row_data->material_code,
                ]);
        } else if ($type == 'group_account') {
            $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount', 'gl_account.group_account_code'))
                ->leftjoin('gl_account', 'zco.cost_element', '=', 'gl_account.gl_account')
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                    'group_account_code' => $query_row_data->group_account_code,
                ]);
        }

        $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
            ->where([
                'product_code' => $query_col_data->product_code,
                'plant_code' => $query_col_data->plant_code,
            ])->groupBy('product_qty', 'periode');

        if ($request->format_data == '0') {
            $temp = explode('-', $request->moth);
            $timemonth = $temp[1] . '-' . $temp[0];

            $total_biaya->where('periode', 'ilike', '%' . $timemonth . '%');
            $kuantum_produksi->where('periode', 'ilike', '%' . $timemonth . '%');
        } else if ($request->format_data == '1') {
            $start_temp = explode('-', $request->start_month);
            $end_temp = explode('-', $request->end_month);
            $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
            $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

            $total_biaya->whereBetween('periode', [$start_date, $end_date]);
            $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
        }

        $total_biaya = $total_biaya->first();
        $kuantum_produksi = $kuantum_produksi->get()->toArray();

        $tot_kuanprod = 0;

        for ($i = 0; $i < count($kuantum_produksi); $i++) {
            $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
        }

        $biaya_perton = 0;

        if ($total_biaya->total_amount != 0 && $tot_kuanprod > 0) {
            $biaya_perton = $total_biaya->total_amount / $tot_kuanprod;
        }

        return $biaya_perton ? $biaya_perton : 0;
    }

    /**
     * Fungsi yang digunakna untuk menghitung data Total Biaya
     *
     * @param [type] $request
     * @param [type] $item
     * @param [type] $material_list
     * @return void
     */
    public function getTotalBiaya($request, $query_col_data, $query_row_data, $type)
    {
        if ($type == 'horizontal') {
            $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                    'material_code' => $query_row_data->material_code,
                ]);
        } else if ($type == 'group_account') {
            $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount', 'gl_account.group_account_code'))
                ->leftjoin('gl_account', 'zco.cost_element', '=', 'gl_account.gl_account')
                ->where([
                    'product_code' => $query_col_data->product_code,
                    'plant_code' => $query_col_data->plant_code,
                    'group_account_code' => $query_row_data->group_account_code,
                ]);
        }

        if ($request->format_data == '0') {
            $temp = explode('-', $request->moth);
            $timemonth = $temp[1] . '-' . $temp[0];

            $total_biaya->where('periode', 'ilike', '%' . $timemonth . '%');
        } else if ($request->format_data == '1') {
            $start_temp = explode('-', $request->start_month);
            $end_temp = explode('-', $request->end_month);
            $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
            $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

            $total_biaya->whereBetween('periode', [$start_date, $end_date]);
        }

        $total_biaya = $total_biaya->first();

        return $total_biaya->total_amount ? $total_biaya->total_amount : 0;
    }
}
