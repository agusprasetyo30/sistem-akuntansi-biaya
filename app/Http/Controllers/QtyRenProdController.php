<?php

namespace App\Http\Controllers;

use App\DataTables\Master\QtyRenProdDataTable;
use App\Exports\QtyRenProdExport;
use App\Models\Asumsi_Umum;
use App\Models\QtyRenProd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class QtyRenProdController extends Controller
{
    public function index(Request $request, QtyRenProdDataTable $qtyrenprodDataTable)
    {
        if ($request->data == 'index') {
            return $qtyrenprodDataTable->render('pages.buku_besar.qty_renprod.index');
        }
        return view('pages.buku_besar.qty_renprod.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "material_code" => 'required',
                "qty_renprod_desc" => 'required',
                "qty_renprod_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $data_asumsi = Asumsi_Umum::where('id', $request->month_year)
                ->first();

            $qty_renprod_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->qty_renprod_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['month_year'] = $data_asumsi->month_year;
            $input['qty_renprod_desc'] = $request->qty_renprod_desc;
            $input['qty_renprod_value'] = $qty_renprod_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            QtyRenProd::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "material_code" => 'required',
                "qty_renprod_desc" => 'required',
                "qty_renprod_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            if (strpos($request->month_year, '-') == true) {
                $data_asumsi = Asumsi_Umum::where([
                    'month_year' => $request->month_year,
                    'version_id' => (int) $request->version
                ])
                    ->first();
            } else {
                $data_asumsi = Asumsi_Umum::where('id', $request->month_year)
                    ->first();
            }

            $qty_renprod_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->qty_renprod_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['month_year'] = $data_asumsi->month_year;
            $input['qty_renprod_desc'] = $request->qty_renprod_desc;
            $input['qty_renprod_value'] = $qty_renprod_value;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            QtyRenProd::where('id', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            QtyRenProd::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        $version = $request->version;

        return Excel::download(new QtyRenProdExport($version), 'qty_renprod.xlsx');
    }
}
