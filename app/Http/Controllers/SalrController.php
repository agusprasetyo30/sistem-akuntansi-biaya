<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_SalrDataTable;
use App\DataTables\Master\SalrDataTable;
use App\Exports\MultipleSheet\MS_SalrExport;
use App\Imports\SalrImport;
use App\Imports\Salrs2Import;
use App\Models\Asumsi_Umum;
use App\Models\CostCenter;
use App\Models\GLAccountFC;
use App\Models\Material;
use App\Models\Salr;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Horizontal\H_Salr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SalrController extends Controller
{
    public function index(Request $request, SalrDataTable $salrDataTable, H_SalrDataTable $h_SalrDataTable)
    {
//        $data = [['product_id' => 1, 'name' => 'Desk'], ['product_id' => 1, 'name' => 'Desk']];
//        $collection = collect($data);
//
//        $collection->put('price', 100);
//
//        $collection->all();
//
//        dd($collection);



        if ($request->data == 'index') {
            return $salrDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.salr.index');
        } elseif ($request->data == 'horizontal') {
            return $h_SalrDataTable->with([
                'format' => $request->format_data,
                'cost_center' => $request->cost_center,
                'version' => $request->version,

                'start_month_versi' => $request->start_month_versi,
                'end_month_versi' => $request->end_month_versi,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'month' => $request->month,
                'inflasi' => $request->inflasi,
            ])->render('pages.buku_besar.salr.index');
        } elseif ($request->data == 'dynamic') {
            $cost_center = Salr::select('salrs.cost_center', 'cost_center.cost_center_desc')
                ->leftjoin('cost_center', 'salrs.cost_center', '=', 'cost_center.cost_center')
                ->groupBy('salrs.cost_center', 'cost_center.cost_center_desc');

            // Periode
            if ($request->format_data == '0') {
                $cost_center->where('salrs.version_id', $request->version);

            } elseif ($request->format_data == '1') {
                $timemonth = Asumsi_Umum::where('id', $request->month)->first();

                $cost_center->where('salrs.periode', $timemonth->month_year)
                    ->where('version_id', $request->version);

            } elseif ($request->format_data == '2') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $cost_center->whereBetween('salrs.periode', [$start_date, $end_date])
                    ->where('version_id', $request->version);

            }

            if ($request->cost_center != 'all') {
                $cost_center->where('salrs.cost_center', $request->cost_center);
            }

            $cost_center = $cost_center->get();

            return response()->json(['code' => 200, 'cost_center' => $cost_center]);
        }
        return view('pages.buku_besar.salr.index');
    }

    public function get_data(Request $request, H_SalrDataTable $h_SalrDataTable){
        if ($request->data == 'horizontal') {

            return $h_SalrDataTable->with([
                'format' => $request->format_data,
                'cost_center' => $request->cost_center,
                'version' => $request->version,

                'start_month_versi' => $request->start_month_versi,
                'end_month_versi' => $request->end_month_versi,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'month' => $request->month,
                'inflasi' => $request->inflasi,
            ])->render('pages.buku_besar.salr.index');


        }elseif ($request->data == 'dynamic') {
            $cost_center = Salr::select('salrs.cost_center', 'cost_center.cost_center_desc')
                ->leftjoin('cost_center', 'salrs.cost_center', '=', 'cost_center.cost_center')
                ->groupBy('salrs.cost_center', 'cost_center.cost_center_desc');

//            dd($request);
            // Periode
            if ($request->format_data == '0') {
                $cost_center->where('salrs.version_id', $request->version);

            } elseif ($request->format_data == '1') {
                $timemonth = Asumsi_Umum::where('id', $request->month)->first();

                $cost_center->where('salrs.periode', $timemonth->month_year)
                    ->where('version_id', $request->version);

            } elseif ($request->format_data == '2') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $cost_center->whereBetween('salrs.periode', [$start_date, $end_date])
                    ->where('version_id', $request->version);

            }

            if ($request->cost_center != 'all') {
                $cost_center->where('salrs.cost_center', $request->cost_center);
            }

            $cost_center = $cost_center->get();

            return response()->json(['code' => 200, 'cost_center' => $cost_center]);
        }
        return view('pages.buku_besar.salr.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "ga_account" => 'required',
                "gl_account" => 'required',
                "cost_center" => 'required',
                "version" => 'required',
                "date" => 'required',
                "value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $asumsi = Asumsi_Umum::where('id', $request->date)->first();

            $input['gl_account_fc'] = $request->gl_account;
            $input['cost_center'] = $request->cost_center;
            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_id;
            $input['periode'] = $asumsi->month_year;
            $input['version_id'] = $asumsi->version_id;
            $input['value'] = (float) str_replace('.', '', str_replace('Rp ', '', $request->value));
            $input['name'] = $request->nama;
            $input['partner_cost_center'] = $request->partner_cost_center;
            $input['username'] = $request->username;
            $input['material_code'] = $request->material;
            $input['document_number'] = $request->document_num;
            $input['document_number_text'] = $request->document_num_desc;
            $input['purchase_order'] = $request->purchase_order;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            Salr::create($input);

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
                "ga_account" => 'required',
                "gl_account" => 'required',
                "cost_center" => 'required',
                "version" => 'required',
                "date" => 'required',
                "value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            try {
                $asumsi = Asumsi_Umum::where('id', $request->date)->first();
            }catch (\Exception $exception){
                $asumsi = Asumsi_Umum::where('month_year', 'ilike', '%'.$request->date.'%')
                    ->where('version_id', $request->version)->first();
            }

            $input['gl_account_fc'] = $request->gl_account;
            $input['cost_center'] = $request->cost_center;
            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_id;
            $input['periode'] = $asumsi->month_year;
            $input['version_id'] = $asumsi->version_id;
            $input['value'] = (float) str_replace('.', '', str_replace('Rp ', '', $request->value));
            $input['name'] = $request->nama;
            $input['partner_cost_center'] = $request->partner_cost_center;
            $input['username'] = $request->username;
            $input['material_code'] = $request->material;
            $input['document_number'] = $request->document_num;
            $input['document_number_text'] = $request->document_num_desc;
            $input['purchase_order'] = $request->purchase_order;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            Salr::where('id', $request->id)
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

            Salr::where('id', $request->id)
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

    public function export(Request $request)
    {
        return Excel::download(new MS_SalrExport(), 'SALR.xlsx');
    }

    public function import(Request $request)
    {
//        dd($request);
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                "detail_version" => 'required',
                'main_version' => 'required'
            ], validatorMsg());

            if ($validator->fails()) {
                return $this->makeValidMsg($validator);
            }

            $asumsi = Asumsi_Umum::where('id', $request->detail_version)->first();
            $master_gl_account_fc = GLAccountFC::get()->pluck('gl_account_fc')->all();
            $master_cost_center = CostCenter::get()->pluck('cost_center')->all();

            $excel = Excel::toArray(new Salrs2Import(), $request->file('file'));
            $header = $excel[0][0];
            unset($excel[0][0]);

            $excel_fix = collect($excel[0])->map(function ($query) use ($asumsi, $header){
                $query = array_combine($header, $query);
                $data['gl_account_fc'] = strval($query['gl_account_fc']);
                $data['cost_center'] = $query['cost_center'];
                $data['periode'] = $asumsi->month_year;
                $data['version_id'] = $asumsi->version_id;
                $data['name'] = $query['name'];
                $data['value'] = $query['value']  != null ? (double) str_replace('.', '', str_replace('Rp ', '', $query['value'])) : 0;
                $data['partner_cost_center'] = $query['partner_cost_center'];
                $data['username'] = $query['username'];
                $data['material_code'] = $query['material_code'];
                $data['document_number'] = $query['document_number'];
                $data['document_number_text'] = $query['document_number_text'];
                $data['purchase_order'] = $query['purchase_order'];
                $data['company_code'] = auth()->user()->company_code;
                $data['created_by'] = auth()->user()->id;
                $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
                return $data;
            });

//            dd($excel_fix);

            if (count($excel_fix) == 0){
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => 'Data Excel Anda Kosong :>'
                ]);
            }


            $gl_account_fc_excel = array_values(array_unique($excel_fix->pluck('gl_account_fc')->toArray()));
            $cost_center_excel = array_values(array_unique($excel_fix->pluck('cost_center')->toArray()));

            $check_gl_account_fc = array_values(array_diff($gl_account_fc_excel, $master_gl_account_fc));
            $check_cost_center = array_values(array_diff($cost_center_excel, $master_cost_center));

            if ($check_gl_account_fc == null and $check_cost_center == null){
//                dd('atas');
                DB::transaction(function () use ($excel_fix, $asumsi){
                    Salr::where('periode', 'ilike', '%' . $asumsi->month_year . '%')
                        ->where('version_id', $asumsi->version_id)
                        ->delete();

                    $result = array_values($excel_fix->toArray());

                    $data = array_chunk($result, 3000);

                    foreach ($data as $items){
                        Salr::insert($items);
                    }
                });
                return setResponse([
                    'code' => 200,
                    'title' => 'Berhasil meng-import data'
                ]);
            }else{
//                dd('bawah');
                $validation_data = ['Gl Account FC ', 'Cost Center '];
                $error = [];
                $msg = '';
                array_push($error, $check_gl_account_fc);
                array_push($error, $check_cost_center);

                foreach ($error as $key => $items){
                    foreach ($items as $item){
                        if ($item != null or $item != ''){
                            $msg .= '<p>' . $validation_data[$key] . $item .' Tidak Ada Pada Master'.'</p>';
                        }else{
                            $msg .= '<p>' . $validation_data[$key] . ' Tidak Boleh Kosong'.'</p>';
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

    public function check(Request $request)
    {
        try {
            $asumsi = Asumsi_Umum::where('id', $request->periode)->first();
//            $timestamp = explode('-', $request->periode);
            $check = Salr::where('periode', 'ilike', '%' . $asumsi->month_year . '%')
                ->first();
            if ($check == null) {
                return response()->json(['code' => 200, 'msg' => 'Data Tidak Ada']);
            } else {
                return response()->json(['code' => 201, 'msg' => 'Data Ada']);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function check_version_salrs(Request $request){
        try {
            $data_versi = Version_Asumsi::where('id', $request->version)->first();
            return response()->json(['code' => 200, 'data' => $data_versi]);
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
