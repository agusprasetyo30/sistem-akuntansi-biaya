<?php

namespace App\DataTables\Master;

use App\Models\Balans;
use App\Models\GLosCC;
use App\Models\MapKategoriBalans;
use App\Models\Master\BalansStore2;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BalansStore2DataTable extends DataTable
{
    public function dataTable($versi, $antrian)
    {
        $query = MapKategoriBalans::select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code', 'kategori_balans.type_kategori_balans')
            ->leftjoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
            ->whereIn('map_kategori_balans.material_code', $antrian)
            ->where('map_kategori_balans.version_id', $versi)
            ->orderBy('map_kategori_balans.material_code', 'ASC')
            ->orderBy('kategori_balans.order_view', 'ASC');


        $datatable = datatables()
            ->eloquent($query)
            ->addColumn('material', function ($query){
                return $query->material_code;
            })
            ->addColumn('keterangan', function ($query){
                return $query->kategori_balans;
            })
            ->addColumn('plant', function ($query){
                return $query->plant_code;
            });

        $main_asumsi = DB::table('asumsi_umum')
            ->where('version_id', $versi)->get();

        $asumsi = $main_asumsi->pluck('month_year')->all();
        $asumsi_balans = $main_asumsi->pluck('month_year')->all();

        $saldo_awal = '2021-12-01 00:00:00';
        array_unshift($asumsi, $saldo_awal);

        $balans = [];

        $glos_cc = GLosCC::select('glos_cc.cost_center','glos_cc.plant_code', 'qty_renprod.qty_renprod_value', 'qty_renprod.asumsi_umum_id', 'glos_cc.material_code')
            ->leftjoin('qty_renprod', 'qty_renprod.cost_center', '=', 'glos_cc.cost_center')
            ->where('qty_renprod.version_id', $versi)
            ->get();


        foreach ($asumsi_balans as $key => $items){
            if ($key > 0 ){
                $balans = DB::table('balans')
                    ->select('balans.*', 'asumsi_umum.month_year')
                    ->leftjoin('asumsi_umum', 'balans.asumsi_umum_id', '=', 'asumsi_umum.id')
                    ->where('asumsi_umum.version_id', $versi)
                    ->get();
            }

            $datatable->addColumn('q'.$key, function ($query) use ($items, $asumsi, $key, $balans, $versi,$main_asumsi, $glos_cc){
                if ($query->kategori_balans_id == 2){
                    $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $versi);
                    return handle_null($result, $result['qty_rendaan_value']);
                }elseif ($query->kategori_balans_id == 3){
                    if ($key > 0 ){
                        try {
                            $nilai_saldo_awal = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = handle_null($nilai_saldo_awal, $nilai_saldo_awal->q);
                        }catch (\Exception $exception){
                            $nilai_saldo_awal = 0;
                        }
                    }else{
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = $nilai_saldo_awal['total_stock'];
                    }

                    $total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $versi);

                    return $nilai_saldo_awal + handle_null($total_daan, $total_daan['qty_rendaan_value']);
                }elseif ($query->kategori_balans_id == 4){
                    $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $versi);
                    return handle_null($result, $result);
                }elseif ($query->kategori_balans_id == 5){
                    if ($key > 0 ){
                        try {
                            $nilai_saldo_awal = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = handle_null($nilai_saldo_awal, $nilai_saldo_awal->q);
                        }catch (\Exception $exception){
                            $nilai_saldo_awal = 0;
                        }
                    }else{
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = handle_null($nilai_saldo_awal['total_stock'], $nilai_saldo_awal['total_stock']);
                    }

                    $total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $versi);

                    $pakai_jual = get_data_balans(4, $query->plant_code, $query->material_code, $items, $versi);

                    $tersedia = $nilai_saldo_awal + handle_null($total_daan['qty_rendaan_value'], $total_daan['qty_rendaan_value']);
                    $result = (double)$tersedia-(double)handle_null($pakai_jual, $pakai_jual);
                    return $result;
                }
                else{
                    return 0;
                }
            })->addColumn('p'.$key, function ($query) use ($items, $asumsi, $key, $balans, $versi, $main_asumsi, $glos_cc){
                if ($query->kategori_balans_id == 2){
                    $quantiti = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $versi);
                    $total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $versi);

                    if (handle_null($quantiti['qty_rendaan_value'], $quantiti['qty_rendaan_value']) != 0){
                        return handle_null($total_daan, $total_daan) / handle_null($quantiti['qty_rendaan_value'], $quantiti['qty_rendaan_value']);
                    }else{
                        return 0;
                    }

                }elseif ($query->kategori_balans_id == 3 || $query->kategori_balans_id == 4){
                    if ($key > 0 ){
                        $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $p_saldo_awal = handle_null($result, $result->q);
                        $nilai_saldo_awal = handle_null($result, $result->nilai) ;
                    }else{
                        $p_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);

                        $p_saldo_awal = handle_null($p_saldo_awal['total_stock'], $p_saldo_awal['total_stock']);
                        $nilai_saldo_awal = handle_null($nilai_saldo_awal['total_value'], $nilai_saldo_awal['total_value']);
                    }

                    $p_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $versi);

                    $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $versi);

                    $p_result = $p_saldo_awal + handle_null($p_total_daan['qty_rendaan_value'], $p_total_daan['qty_rendaan_value']);
                    $nilai_result = $nilai_saldo_awal + handle_null($nilai_total_daan, $nilai_total_daan);

                    if ($p_result != 0){
                        return $nilai_result / $p_result;
                    }else{
                        return 0;
                    }
                }elseif ($query->kategori_balans_id == 5){
                    if ($key > 0 ){
                        $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $q_saldo_awal = handle_null($result, $result->q);
                        $nilai_saldo_awal = handle_null($result, $result->nilai);
                    }else{
                        $q_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $q_saldo_awal = handle_null($q_saldo_awal['total_stock'], $q_saldo_awal['total_stock']);
                        $nilai_saldo_awal = handle_null($nilai_saldo_awal['total_value'], $nilai_saldo_awal['total_value']);
                    }

                    $q_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $versi);
                    $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $versi);

                    $q_tersedia = $q_saldo_awal + handle_null($q_total_daan['qty_rendaan_value'], $q_total_daan['qty_rendaan_value']);

                    $nilai_tersedia = $nilai_saldo_awal + handle_null($nilai_total_daan, $nilai_total_daan);

                    $q_pakai_jual = get_data_balans(4, $query->plant_code, $query->material_code, $items, $versi);

                    if ($q_tersedia != 0){
                        $p_tersedia = $nilai_tersedia / $q_tersedia;

                        $total_pakai_jual = handle_null($q_pakai_jual, $q_pakai_jual) * $p_tersedia;

                        $hasil_q_saldo_akhir = $q_tersedia - $q_pakai_jual;
                        $hasil_nilai_saldo_akhir = $nilai_tersedia - $total_pakai_jual;

                        if ($hasil_q_saldo_akhir != 0){
                            return $hasil_nilai_saldo_akhir / $hasil_q_saldo_akhir;
                        }else{
                            return 0;
                        }
                    }else{
                        return 0;
                    }
                }
                else{
                    return 0;
                }
            })->addColumn('nilai'.$key, function ($query) use ($items, $asumsi, $key, $balans, $versi){
                if ($query->kategori_balans_id == 2){
                    $result = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $versi);
                    return handle_null($result, $result);
                }elseif ($query->kategori_balans_id == 3){
                    if ($key > 0 ){
                        $nilai_saldo_awal = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = handle_null($nilai_saldo_awal, $nilai_saldo_awal->nilai);
                    }else{
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = handle_null($nilai_saldo_awal['total_value'], $nilai_saldo_awal['total_value']);
                    }

                    $total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $versi);

                    return $nilai_saldo_awal + handle_null($total_daan, $total_daan);
                }elseif ($query->kategori_balans_id == 4){
                    $p_pakai_jual = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $versi);

                    if ($key > 0 ){
                        $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $p_saldo_awal = handle_null($result, $result->q);
                        $nilai_saldo_awal = handle_null($result, $result->nilai);
                    }else{
                        $p_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);

                        $p_saldo_awal = handle_null($p_saldo_awal['total_stock'], $p_saldo_awal['total_stock']);
                        $nilai_saldo_awal = handle_null($nilai_saldo_awal['total_value'], $nilai_saldo_awal['total_value']);
                    }


                    $p_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $versi);

                    $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $versi);

                    $p_result = $p_saldo_awal + handle_null($p_total_daan['qty_rendaan_value'], $p_total_daan['qty_rendaan_value']);

                    $nilai_result = $nilai_saldo_awal + handle_null($nilai_total_daan, $nilai_total_daan);

                    if ($p_result != 0){
                        $value = $nilai_result / $p_result;
                    }else{
                        $value = 0;
                    }
                    return handle_null($p_pakai_jual, $p_pakai_jual) * $value;

                }elseif ($query->kategori_balans_id == 5){
                    if ($key > 0 ){
                        $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $q_saldo_awal = handle_null($result, $result->q);
                        $nilai_saldo_awal = handle_null($result, $result->nilai);
                    }else{
                        $q_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $q_saldo_awal = handle_null($q_saldo_awal['total_stock'], $q_saldo_awal['total_stock']);
                        $nilai_saldo_awal = handle_null($nilai_saldo_awal['total_value'], $nilai_saldo_awal['total_value']);
                    }

                    $q_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $versi);
                    $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $versi);

                    $q_tersedia = $q_saldo_awal + handle_null($q_total_daan['qty_rendaan_value'], $q_total_daan['qty_rendaan_value']);

                    $nilai_tersedia = $nilai_saldo_awal + handle_null($nilai_total_daan, $nilai_total_daan);

                    $q_pakai_jual = get_data_balans(4, $query->plant_code, $query->material_code, $items, $versi);

                    if ($q_tersedia != 0){
                        $p_tersedia = $nilai_tersedia / $q_tersedia;

                        $total_pakai_jual = handle_null($q_pakai_jual, $q_pakai_jual) * $p_tersedia;

                        $hasil_nilai_saldo_akhir = $nilai_tersedia - $total_pakai_jual;

                        return $hasil_nilai_saldo_akhir;
                    }else{
                        return 0;
                    }
                }else{
                    return 0;
                }
            })->addColumn('asumsi'.$key, function ($query) use ($main_asumsi, $key){
                return $main_asumsi[$key]->id;
            });

            $parse = $datatable;
            $data_temp = $parse->toArray();
            $input_value = [];
            foreach ($data_temp['data'] as $key1 => $data_insert){
                $input['asumsi_umum_id'] = $data_insert['asumsi'.$key];
                $input['kategori_balans_id'] = $data_insert['kategori_balans_id'];
                $input['plant_code'] = $data_insert['plant_code'];
                $input['material_code'] = $data_insert['material'];
                $input['q'] =(double) str_replace('Rp ', '', $data_insert['q'.$key]);
                $input['p'] =(double) str_replace('Rp ', '', $data_insert['p'.$key]);
                $input['nilai'] =(double) str_replace('Rp ', '', $data_insert['nilai'.$key]);
                $input['company_code'] = auth()->user()->company_code;
                $input['created_by'] = auth()->user()->id;
                $input['created_at'] = Carbon::now()->format('Y-m-d');
                $input['updated_at'] = Carbon::now()->format('Y-m-d');
                array_push($input_value, $input);
            }
            $result = array_chunk($input_value, 100);

//            dd($result);
            foreach ($result as $item){
                Balans::insert($item);
            }
        }

        return $datatable;

    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('master\balansstore2-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Master\BalansStore2_' . date('YmdHis');
    }
}
