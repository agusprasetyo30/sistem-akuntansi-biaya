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
    public function dataTable($query)
    {
//        dd($this->antrian);
        $query = MapKategoriBalans::select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'kategori_balans.kategori_balans')
            ->leftjoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
            ->whereIn('map_kategori_balans.material_code', array_unique($this->antrian))
            ->where('map_kategori_balans.version_id', $this->version)
//            ->where('map_kategori_balans.kategori_balans_id', 2)
            ->orderBy('map_kategori_balans.material_code', 'ASC')
            ->orderBy('map_kategori_balans.kategori_balans_id', 'ASC');

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


//        dd($balans, $this->version);

//        dd($main_asumsi, $asumsi, $asumsi_balans);

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
//                    var_dump($key);
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
                            return rupiah($result->nilai / $result->q);
                        }else{
                            return rupiah(0);
                        }
                    }else{
                        $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                        if ($result['total_stock'] != 0){
                            return rupiah($result['total_value']/$result['total_stock']);
                        }else{
                            return rupiah(0);
                        }
                    }
                }elseif ($query->kategori_balans_id == 2){
                    $quantiti = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $this->version);
                    $total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                    if ($quantiti['qty_rendaan_value'] != 0){
                        return rupiah($total_daan / $quantiti['qty_rendaan_value']);
                    }else{
                        return rupiah(0);
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
                        return rupiah($nilai_result / $p_result);
                    }else{
                        return rupiah(0);
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


//                        dd($hasil_total_saldo_akhir, $p_result, $hasil_p_saldo_akhir);
                        if ($hasil_q_saldo_akhir != 0){
                            return rupiah($hasil_nilai_saldo_akhir / $hasil_q_saldo_akhir);
                        }else{
                            return rupiah(0);
                        }
                    }else{
                        return rupiah(0);
                    }
                }else{
                    return 0;
                }
            })->addColumn('nilai'.$key, function ($query) use ($items, $asumsi, $key, $balans){
                if ($query->kategori_balans_id == 1){
                    if ($key > 0 ){
                        $result = get_data_balans_db($balans, $query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                        return rupiah($result->nilai);
                    }else{
                        $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $asumsi[$key]);
                        return rupiah($result['total_value']);
                    }
                }elseif ($query->kategori_balans_id == 2){
                    $result = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);
                    return rupiah($result);
                }elseif ($query->kategori_balans_id == 3){
                    if ($key > 0 ){
                        $nilai_saldo_awal = get_data_balans_db($balans, 1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = $nilai_saldo_awal->nilai;
                    }else{
                        $nilai_saldo_awal = get_data_balans(1, $query->plant_code, $query->material_code, $asumsi[$key]);
                        $nilai_saldo_awal = $nilai_saldo_awal['total_value'];
                    }

                    $total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                    return rupiah($nilai_saldo_awal + $total_daan);
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
                    return rupiah($p_pakai_jual * $value);

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

                        return rupiah($hasil_nilai_saldo_akhir);
                    }else{
                        return rupiah(0);
                    }
                }else{
                    return 0;
                }
            })->addColumn('asumsi'.$key, function ($query) use ($main_asumsi, $key){
                return $main_asumsi[$key]->id;
            });

//            if ($key == 1){
//                dd($datatable->toArray());
//            }

            $parse = $datatable;
            if ($this->save == true){
                DB::transaction(function () use ($parse, $key){
//                    Balans::leftjoin('asumsi_umum', 'balans.asumsi_umum_id', '=', 'asumsi_umum.id')
//                        ->where('asumsi_umum.version_id', 1)
//                        ->delete();

                    $data_temp = $parse->toArray();
                    $input_fix = [];
                    foreach ($data_temp['data'] as $data_insert){
                        $input['asumsi_umum_id'] = $data_insert['asumsi'.$key];
                        $input['kategori_balans_id'] = $data_insert['kategori_balans_id'];
                        $input['plant_code'] = $data_insert['plant_code'];
                        $input['material_code'] = $data_insert['material'];
                        $input_nilai['q'] =(double) str_replace('.', '', str_replace('Rp ', '', $data_insert['q'.$key]));
                        $input_nilai['p'] =(double) str_replace('.', '', str_replace('Rp ', '', $data_insert['p'.$key]));
                        $input_nilai['nilai'] =(double) str_replace('.', '', str_replace('Rp ', '', $data_insert['nilai'.$key]));
                        $input_nilai['company_code'] = auth()->user()->company_code;
                        $input_nilai['created_by'] = auth()->user()->id;
                        $input_nilai['created_at'] = Carbon::now()->format('Y-m-d');
                        $input_nilai['updated_at'] = Carbon::now()->format('Y-m-d');
//                        array_push($input_fix, [$input, $input_nilai]);
                        Balans::updateOrCreate($input, $input_nilai);
                    }
//                    DB::table('balans')->updateOrCreate($input_fix);
                });

            }
//            if ($key == 4){
//                dd($datatable->toArray());
//            }
        }
//        dd($datatable->toArray());

        return $datatable;
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
