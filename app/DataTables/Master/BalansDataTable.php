<?php

namespace App\DataTables\Master;

use App\Models\MapKategoriBalans;
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

        $asumsi = DB::table('asumsi_umum')
            ->select('asumsi_umum.month_year')
            ->where('version_id', $this->version)
            ->pluck('asumsi_umum.month_year')->all();

        $asumsi_balans = $asumsi;

        $saldo_awal = '2021-12-01 00:00:00';
        array_unshift($asumsi, $saldo_awal);


        foreach ($asumsi_balans as $key=> $items){
            $datatable->addColumn('q'.$key, function ($query) use ($items){
                if ($query->kategori_balans_id == 1){
                    $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items);
                    return $result['total_stock'];
                }elseif ($query->kategori_balans_id == 2){
                    $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $this->version);
                    return $result['qty_rendaan_value'];
                }else{
                    return 0;
                }
            })->addColumn('p'.$key, function ($query) use ($items){
                if ($query->kategori_balans_id == 1){
                    $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items);
                    if ($result['total_stock'] != 0){
                        return $result['total_value']/$result['total_stock'];
                    }else{
                        return 0;
                    }
                }elseif ($query->kategori_balans_id == 2 || $query->kategori_balans_id == 3){
                    $quantiti = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items, $this->version);
                    $total_daan = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);

                    return rupiah($total_daan / $quantiti['qty_rendaan_value']);
                }else{
                    return 0;
                }
            })->addColumn('nilai'.$key, function ($query) use ($items){
                if ($query->kategori_balans_id == 1){
                    $result = get_data_balans($query->kategori_balans_id, $query->plant_code, $query->material_code, $items);
                    return $result['total_value'];
                }elseif ($query->kategori_balans_id == 2){
                    $result = get_data_balans('total_daan', $query->plant_code, $query->material_code, $items, $this->version);
                    return rupiah($result);
                }else{
                    return 0;
                }
            });
        }

        dd($datatable->toArray());

//        return $datatable;
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
