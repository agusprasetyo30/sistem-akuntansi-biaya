<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_ZcoDataTable;
use App\DataTables\Master\H_ZcoGroupAccountDataTable;
use App\DataTables\Master\ZcoDataTable;
use App\Exports\MultipleSheet\MS_ZcoExport;
use App\Exports\Horizontal\ZCOExport;
use App\Exports\Horizontal\ZCOGroupAccountExport;
use App\Imports\Zco2Import;
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
    public function index(Request $request, ZcoDataTable $zcoDataTable)
    {
        if ($request->data == 'index') {
            return $zcoDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.zco.index');
        }

        return view('pages.buku_besar.zco.index');
    }

    public function get_data(Request $request, H_ZcoDataTable $h_zcoDataTable)
    {
        if ($request->data == 'horizontal') {
            return $h_zcoDataTable->with([
                'material' => $request->material,
                'plant' => $request->plant,
                'format' => $request->format_data,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'moth' => $request->moth,
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

            if ($request->format_data == '0') {
                $material->where('zco.periode', 'ilike', '%-' . check_month_by_name($request->moth) . '-%');
            } else if ($request->format_data == '1') {
                $start_month = '2000-' . check_month_by_name($request->start_month) . '-01 00:00:00';
                $end_month = '2000-' . check_month_by_name($request->end_month) . '-01 00:00:00';

                $material->whereBetween('zco.periode', [$start_month, $end_month]);
            }
            // dd($request->material, $request->plant);
            $material = $material->get();
            // dd($material);
            return response()->json(['code' => 200, 'material' => $material]);
        }

        return view('pages.buku_besar.zco.index');
    }

    public function get_data_ga(Request $request, H_ZcoGroupAccountDataTable $h_zcogroupaccountDataTable)
    {
        if ($request->data == 'horizontal_group_account') {
            return $h_zcogroupaccountDataTable->with([
                'material' => $request->material,
                'plant' => $request->plant,
                'format' => $request->format_data,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'moth' => $request->moth,
                'version' => $request->version,
                'company' => $request->company,
            ])->render('pages.buku_besar.zco.index');
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

            if ($request->format_data == '0') {
                $group_account->where('zco.periode', 'ilike', '%-' . check_month_by_name($request->moth) . '-%');
            } else if ($request->format_data == '1') {
                $start_month = '2000-' . check_month_by_name($request->start_month) . '-01 00:00:00';
                $end_month = '2000-' . check_month_by_name($request->end_month) . '-01 00:00:00';

                $group_account->whereBetween('zco.periode', [$start_month, $end_month]);
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
                "periode" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['version_id'] = (int) $request->version;
            $input['periode'] = '2000-' . check_month_by_name($request->periode) . '-01 00:00:00';
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
                "periode" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['version_id'] = (int) $request->version;
            $input['periode'] = '2000-' . check_month_by_name($request->periode) . '-01 00:00:00';
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

            $asumsi = Asumsi_Umum::where('id', $request->detail_version_import)->first();
            $master_plant = Plant::get()->pluck('plant_code')->all();
            $master_product = Material::get()->pluck('material_code')->all();
            $master_cost_element = GLAccount::get()->pluck('gl_account')->all();
            $excel = Excel::toArray(new Zco2Import(), $request->file('file'));
            $header = $excel[0][0];
            unset($excel[0][0]);

            $excel_fix =  collect($excel[0])->map(function ($query) use ($header, $request) {
                $query = array_combine($header, $query);
                $data['plant_code'] = $query['plant_code'];
                $data['periode'] = '2000-' . check_month_by_name($request->periode) . '-01 00:00:00';
                $data['version_id'] = $request->version;
                $data['product_code'] = strval($query['product_code']);
                $data['product_qty'] = $query['product_qty'];
                $data['cost_element'] = strval($query['cost_element']);
                $data['material_code'] = $query['material_code'];
                $data['total_qty'] = $query['total_qty'];
                $data['currency'] = $query['currency'];
                $data['total_amount'] = $query['total_amount'];
                $data['unit_price_product'] = $query['unit_price_product'];
                $data['company_code'] = auth()->user()->company_code;
                $data['created_by'] = auth()->user()->id;
                $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

                return $data;
            });

            if (count($excel_fix) == 0) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => 'Data Excel Anda Kosong :>'
                ]);
            }

            $plant_excel = array_values(array_unique($excel_fix->pluck('plant_code')->toArray()));
            $product_excel = array_values(array_unique($excel_fix->pluck('product_code')->toArray()));
            $cost_element_excel = array_values(array_unique($excel_fix->pluck('cost_element')->toArray()));

            $check_plant = array_values(array_diff($plant_excel, $master_plant));
            $check_product = array_values(array_diff($product_excel, $master_product));
            $check_cost_element = array_values(array_diff($cost_element_excel, $master_cost_element));

            if ($check_plant == null && $check_product == null && $check_cost_element == null) {
                DB::transaction(function () use ($excel_fix, $request) {
                    Zco::where('periode', 'ilike', '%-' . check_month_by_name($request->periode) . '-%')
                        ->where('version_id', $request->version)
                        ->delete();

                    $result = array_values($excel_fix->toArray());

                    $data = array_chunk($result, 3000);

                    foreach ($data as $items) {
                        Zco::insert($items);
                    }
                });

                return setResponse([
                    'code' => 200,
                    'title' => 'Berhasil meng-import data'
                ]);
            } else {
                $validation_data = ['Plant ', 'Produk ', 'Cost Element '];
                $error = [];
                $msg = '';

                array_push($error, $check_plant);
                array_push($error, $check_product);
                array_push($error, $check_cost_element);

                foreach ($error as $key => $items) {
                    foreach ($items as $item) {
                        if ($item != null or $item != '') {
                            $msg .= '<p>' . $validation_data[$key] . $item . ' Tidak Ada Pada Master' . '</p>';
                        } else {
                            $msg .= '<p>' . $validation_data[$key] . ' Tidak Boleh Kosong' . '</p>';
                        }
                    }
                }

                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => $msg
                ]);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
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
            "version" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        try {
            $check = Zco::where('periode', 'ilike', '%-' . check_month_by_name($request->periode) . '-%')
                ->where('version_id', $request->version)
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
        $material_list = Material::orderBy('material_code', 'asc');

        if (auth()->user()->mapping_akses('zco')->company_code != 'all') {
            $material_list = $material_list->where('material.company_code', auth()->user()->mapping_akses('zco')->company_code);
        }

        // Product list for header dinamis
        $product_list = Zco::select('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name', 'material.material_code')
            ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
            ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
            ->groupBy('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name', 'material.material_code')
            ->orderBy('material.material_code', 'asc');

        if (auth()->user()->mapping_akses('zco')->company_code != 'all') {
            $product_list = $product_list->where('zco.company_code', auth()->user()->mapping_akses('zco')->company_code);
        }

        if ($request->version) {
            $product_list = $product_list->where('zco.version_id', $request->version);
        }

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

        $material_list = $material_list->get();
        $product_list = $product_list->get();

        // Dibuat variabel index temporary dikarenakan case nya ada index yang tidak diawali dengan 0
        $key_temp = 0;

        // Proses memasukan data berdasarkan rule/aturan
        foreach ($material_list->get() as $query) {
            foreach ($product_list->get() as $item) {
                array_push($temporary_value['harga_satuan'], ['key' => $key_temp, 'value' => $this->getHargaSatuanCount($request, $item, $query, 'horizontal')]);
                array_push($temporary_value['cr'], ['key' => $key_temp, 'value' => $this->getCRCount($request, $item, $query, 'horizontal')]);
                array_push($temporary_value['biaya_per_ton'], ['key' => $key_temp, 'value' => $this->getBiayaPerTon($request, $item, $query, 'horizontal')]);
                array_push($temporary_value['total_biaya'], ['key' => $key_temp, 'value' => $this->getTotalBiaya($request, $item, $query, 'horizontal')]);

                $key_temp++;
            }

            $key_temp = 0;
        }

        // Menghitung jumlah total asumsi umum sebagai acuan index
        $product_index_count = $product_list->get()->count() - 1;

        // Memisahkan data array yang disesuaikan dengan array key & transaksi (p, q, <nila></nila>i)
        $fixed_value['harga_satuan'] = getSeparateValue($temporary_value['harga_satuan'], $product_index_count);
        $fixed_value['cr'] = getSeparateValue($temporary_value['cr'], $product_index_count);
        $fixed_value['biaya_per_ton'] = getSeparateValue($temporary_value['biaya_per_ton'], $product_index_count);
        $fixed_value['total_biaya'] = getSeparateValue($temporary_value['total_biaya'], $product_index_count);

        // Menghitung total dari kolom
        for ($i = 0; $i < $product_list->get()->count(); $i++) {
            $total['harga_satuan'][$i] = array_sum($fixed_value['harga_satuan'][$i]);
            $total['cr'][$i] = array_sum($fixed_value['cr'][$i]);
            $total['biaya_per_ton'][$i] = array_sum($fixed_value['biaya_per_ton'][$i]);
            $total['total_biaya'][$i] = array_sum($fixed_value['total_biaya'][$i]);
        }

        $data = [
            'material_lists'   => $material_list->get(),
            'product_lists'    => $product_list->get(),
            'fixed_value_data' => $fixed_value,
            'total'            => $total
        ];

        return Excel::download(new ZCOExport($data), "ZCO Horizontal.xlsx");

        // return view('pages.buku_besar.zco.export_horizontal', $data);
    }

    public function exportGroupAccount(Request $request)
    {
        // Group Account by user company code
        $group_account_list = GroupAccount::select('group_account_code', 'group_account_desc');

        if (auth()->user()->mapping_akses('zco')->company_code != 'all') {
            $group_account_list = $group_account_list->where('group_account.company_code', auth()->user()->mapping_akses('zco')->company_code);
        }

        // Product list for header dinamis
        $product_list = Zco::select('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name')
            ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
            ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
            ->groupBy('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name');

        if (auth()->user()->mapping_akses('zco')->company_code != 'all') {
            $product_list = $product_list->where('zco.company_code', auth()->user()->mapping_akses('zco')->company_code);
        }

        if ($request->material != 'all') {
            $product_list = $product_list->where('product_code',  $request->material);
        }

        if ($request->plant != 'all') {
            $product_list = $product_list->where('plant_code', $request->plant);
        }

        // if ($request->format_data == '0') {
        //     $temp = explode('-', $request->moth);
        //     $timemonth = $temp[1] . '-' . $temp[0];

        //     $product_list = $product_list->where('periode', 'ilike', '%' . $timemonth . '%');
        // } else if ($request->format_data == '1') {
        //     $start_temp = explode('-', $request->start_month);
        //     $end_temp = explode('-', $request->end_month);
        //     $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
        //     $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

        //     $product_list = $product_list->whereBetween('periode', [$start_date, $end_date]);
        // }

        $temporary_value['harga_satuan'] = [];
        $temporary_value['cr'] = [];
        $temporary_value['biaya_per_ton'] = [];
        $temporary_value['total_biaya'] = [];

        $group_account_list = $group_account_list->get();
        $product_list = $product_list->get();

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
                $total_qty->where('periode', 'ilike', '%' . $request->moth . '%');
                $total_biaya->where('periode', 'ilike', '%' . $request->moth . '%');
                $kuantum_produksi->where('periode', 'ilike', '%' . $request->moth . '%');
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
                $total_qty->where('periode', 'ilike', '%' . $request->moth . '%');
                $kuantum_produksi->where('periode', 'ilike', '%' . $request->moth . '%');
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
            $total_biaya->where('periode', 'ilike', '%' . $request->moth . '%');
            $kuantum_produksi->where('periode', 'ilike', '%' . $request->moth . '%');
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
            $total_biaya->where('periode', 'ilike', '%' . $request->moth . '%');
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
