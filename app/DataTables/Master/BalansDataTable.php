<?php

namespace App\DataTables\Master;

use App\Models\Balans;
use App\Models\MapKategoriBalans;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BalansDataTable extends DataTable
{
    public function dataTable()
    {
        $query = MapKategoriBalans::select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code', 'kategori_balans.kategori_balans')
            ->leftjoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
            ->whereIn('map_kategori_balans.material_code', $this->antrian)
            ->where('map_kategori_balans.version_id', $this->version)
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
            ->where('version_id', $this->version)->get();

        $asumsi = $main_asumsi->pluck('month_year')->all();
        $asumsi_balans = $main_asumsi->pluck('month_year')->all();

        $saldo_awal = '2021-12-01 00:00:00';
        array_unshift($asumsi, $saldo_awal);

        $balans = DB::table('balans')
            ->select('balans.*', 'asumsi_umum.month_year')
            ->leftjoin('asumsi_umum', 'balans.asumsi_umum_id', '=', 'asumsi_umum.id')
            ->where('asumsi_umum.version_id', $this->version)
            ->get();

        if ($this->save == true){

            foreach ($asumsi_balans as $key => $items){
                if ($key > 0 ){
                    $balans = DB::table('balans')
                        ->select('balans.*', 'asumsi_umum.month_year')
                        ->leftjoin('asumsi_umum', 'balans.asumsi_umum_id', '=', 'asumsi_umum.id')
                        ->where('asumsi_umum.version_id', $this->version)
                        ->get();
                }
                $datatable->addColumn('q'.$key, function ($query) use ($items, $asumsi, $key, $balans){
                    if ($query->kategori_balans_id == 1){
                        if ($key > 0 ){
                            $result = get_data_balans_db($balans, $query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                            return $result->q;
                        }else{

                            $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                            return $result['total_stock'];
                        }
                    }elseif ($query->kategori_balans_id == 2){
                        $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $this->version);
                        return $result['qty_rendaan_value'];
                    }elseif ($query->kategori_balans_id == 3){
                        if ($key > 0 ){
                            $nilai_saldo_awal = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = $nilai_saldo_awal->q;
                        }else{
                            $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = $nilai_saldo_awal['total_stock'];
                        }

                        $total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $this->version);

                        return $nilai_saldo_awal + $total_daan['qty_rendaan_value'];
                    }elseif ($query->kategori_balans_id == 4){
                        $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $this->version);
                        return $result;
                    }elseif ($query->kategori_balans_id == 5){
                        if ($key > 0 ){
                            $nilai_saldo_awal = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = $nilai_saldo_awal->q;
                        }else{
                            $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = $nilai_saldo_awal['total_stock'];
                        }

                        $total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $this->version);

                        $pakai_jual = get_data_balans(4, $query->plant_code, $query->material_code, $items, $this->version);

                        $tersedia = $nilai_saldo_awal + $total_daan['qty_rendaan_value'];
                        $result = (double)$tersedia-(double)$pakai_jual;
                        return $result;
                    }else{
                        return 0;
                    }
                })->addColumn('p'.$key, function ($query) use ($items, $asumsi, $key, $balans){
                    if ($query->kategori_balans_id == 1){
                        if ($key > 0 ){
                            $result = get_data_balans_db($balans, $query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                            if ($result->q != 0){
                                return $result->nilai / $result->q;
                            }else{
                                return 0;
                            }
                        }else{
                            $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                            if ($result['total_stock'] != 0){
                                return $result['total_value']/$result['total_stock'];
                            }else{
                                return 0;
                            }
                        }
                    }elseif ($query->kategori_balans_id == 2){
                        $quantiti = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $this->version);
                        $total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                        if ($quantiti['qty_rendaan_value'] != 0){
                            return $total_daan / $quantiti['qty_rendaan_value'];
                        }else{
                            return 0;
                        }

                    }elseif ($query->kategori_balans_id == 3 || $query->kategori_balans_id == 4){
                        if ($key > 0 ){
                            $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $p_saldo_awal = $result->q;
                            $nilai_saldo_awal = $result->nilai;
                        }else{
                            $p_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);

                            $p_saldo_awal = $p_saldo_awal['total_stock'];
                            $nilai_saldo_awal = $nilai_saldo_awal['total_value'];
                        }

                        $p_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $this->version);

                        $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                        $p_result = $p_saldo_awal + $p_total_daan['qty_rendaan_value'];
                        $nilai_result = $nilai_saldo_awal + $nilai_total_daan;

                        if ($p_result != 0){
                            return $nilai_result / $p_result;
                        }else{
                            return 0;
                        }
                    }elseif ($query->kategori_balans_id == 5){
                        if ($key > 0 ){
                            $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $q_saldo_awal = $result->q;
                            $nilai_saldo_awal = $result->nilai;
                        }else{
                            $q_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $q_saldo_awal = $q_saldo_awal['total_stock'];
                            $nilai_saldo_awal = $nilai_saldo_awal['total_value'];
                        }

                        $q_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $this->version);
                        $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                        $q_tersedia = $q_saldo_awal + $q_total_daan['qty_rendaan_value'];

                        $nilai_tersedia = $nilai_saldo_awal + $nilai_total_daan;

                        $q_pakai_jual = get_data_balans(4, $query->plant_code, $query->material_code, $items, $this->version);

                        if ($q_tersedia != 0){
                            $p_tersedia = $nilai_tersedia / $q_tersedia;

                            $total_pakai_jual = $q_pakai_jual * $p_tersedia;

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
                    }else{
                        return 0;
                    }
                })->addColumn('nilai'.$key, function ($query) use ($items, $asumsi, $key, $balans){
                    if ($query->kategori_balans_id == 1){
                        if ($key > 0 ){
                            $result = get_data_balans_db($balans, $query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                            return $result->nilai;
                        }else{
                            $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                            return $result['total_value'];
                        }
                    }elseif ($query->kategori_balans_id == 2){
                        $result = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);
                        return $result;
                    }elseif ($query->kategori_balans_id == 3){
                        if ($key > 0 ){
                            $nilai_saldo_awal = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = $nilai_saldo_awal->nilai;
                        }else{
                            $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = $nilai_saldo_awal['total_value'];
                        }

                        $total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                        return $nilai_saldo_awal + $total_daan;
                    }elseif ($query->kategori_balans_id == 4){
                        $p_pakai_jual = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $this->version);

                        if ($key > 0 ){
                            $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $p_saldo_awal = $result->q;
                            $nilai_saldo_awal = $result->nilai;
                        }else{
                            $p_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);

                            $p_saldo_awal = $p_saldo_awal['total_stock'];
                            $nilai_saldo_awal = $nilai_saldo_awal['total_value'];
                        }


                        $p_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $this->version);

                        $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                        $p_result = $p_saldo_awal + $p_total_daan['qty_rendaan_value'];

                        $nilai_result = $nilai_saldo_awal + $nilai_total_daan;

                        if ($p_result != 0){
                            $value = $nilai_result / $p_result;
                        }else{
                            $value = 0;
                        }
                        return $p_pakai_jual * $value;

                    }elseif ($query->kategori_balans_id == 5){
                        if ($key > 0 ){
                            $result = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $q_saldo_awal = $result->q;
                            $nilai_saldo_awal = $result->nilai;
                        }else{
                            $q_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                            $q_saldo_awal = $q_saldo_awal['total_stock'];
                            $nilai_saldo_awal = $nilai_saldo_awal['total_value'];
                        }

                        $q_total_daan = get_data_balans(2, $query->plant_code, $query->material_code, $items, $this->version);
                        $nilai_total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                        $q_tersedia = $q_saldo_awal + $q_total_daan['qty_rendaan_value'];

                        $nilai_tersedia = $nilai_saldo_awal + $nilai_total_daan;

                        $q_pakai_jual = get_data_balans(4, $query->plant_code, $query->material_code, $items, $this->version);

                        if ($q_tersedia != 0){
                            $p_tersedia = $nilai_tersedia / $q_tersedia;

                            $total_pakai_jual = $q_pakai_jual * $p_tersedia;


                            $hasil_q_saldo_akhir = $q_tersedia - $q_pakai_jual;
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
                if ($this->save == true){
                    DB::transaction(function () use ($parse, $key){

                        $data_temp = $parse->toArray();
                        foreach ($data_temp['data'] as $data_insert){
                            $input['asumsi_umum_id'] = $data_insert['asumsi'.$key];
                            $input['kategori_balans_id'] = $data_insert['kategori_balans_id'];
                            $input['plant_code'] = $data_insert['plant_code'];
                            $input['material_code'] = $data_insert['material'];
                            $input_nilai['q'] =(double) str_replace('Rp ', '', $data_insert['q'.$key]);
                            $input_nilai['p'] =(double) str_replace('Rp ', '', $data_insert['p'.$key]);
                            $input_nilai['nilai'] =(double) str_replace('Rp ', '', $data_insert['nilai'.$key]);
                            $input_nilai['company_code'] = auth()->user()->company_code;
                            $input_nilai['created_by'] = auth()->user()->id;
                            $input_nilai['created_at'] = Carbon::now()->format('Y-m-d');
                            $input_nilai['updated_at'] = Carbon::now()->format('Y-m-d');
                            Balans::updateOrCreate($input, $input_nilai);
                        }
                    });

                }
//                dd($datatable->toArray());
            }
            return $datatable;
        }else{
            foreach ($asumsi_balans as $key => $items){
                $datatable->addColumn('q'.$key, function ($query) use ($items, $asumsi, $key, $balans, $main_asumsi){
                    $result = $balans->where('kategori_balans_id', $query->kategori_balans_id)
                        ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                        ->where('company_code', $query->company_code)
                        ->where('plant_code', $query->plant_code)
                        ->where('material_code', $query->material_code)
                        ->first();

                    return handle_null($result, $result->q);
                })->addColumn('p'.$key, function ($query) use ($items, $asumsi, $key, $balans, $main_asumsi){
                    $result = $balans->where('kategori_balans_id', $query->kategori_balans_id)
                        ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                        ->where('company_code', $query->company_code)
                        ->where('plant_code', $query->plant_code)
                        ->where('material_code', $query->material_code)
                        ->first();

                    return rupiah(handle_null($result, $result->p));
                })->addColumn('nilai'.$key, function ($query) use ($items, $asumsi, $key, $balans, $main_asumsi){
                    $result = $balans->where('kategori_balans_id', $query->kategori_balans_id)
                        ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                        ->where('company_code', $query->company_code)
                        ->where('plant_code', $query->plant_code)
                        ->where('material_code', $query->material_code)
                        ->first();

                    return rupiah(handle_null($result, $result->nilai));
                })->addColumn('asumsi'.$key, function ($query) use ($main_asumsi, $key){
                    return $main_asumsi[$key]->id;
                });
            }

            return $datatable;
        }

    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_balans')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
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
        return 'Master\Balans_' . date('YmdHis');
    }
}