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
        $query = MapKategoriBalans::select('map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'kategori_balans.kategori_balans')
            ->leftjoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
            ->whereIn('map_kategori_balans.material_code', array_unique($this->antrian))
            ->where('map_kategori_balans.version_id', $this->version)
            ->orderBy('map_kategori_balans.material_code', 'ASC')
//            ->orderByRaw('FIELD(kategori_balans.id, [6, 1, 2, 3, 4, 5])');
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
            ->where('version_id', $this->version)
            ->get();

        $data = 0;

        foreach ($asumsi as $key=> $items){
            $data = $data + 10;
            $datatable->addColumn('q'.$key, function ($query) use ($data){
                return $data;
            })->addColumn('p'.$key, function ($query) use ($data){
                return $data;
            })->addColumn('nilai'.$key, function ($query) use ($data){
                return $data;
            });
        }

        dd($datatable->toArray());

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
