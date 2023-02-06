<?php

namespace App\DataTables\Master;

use App\Models\MapKategoriBalans;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MapKategoriBalansDataTable extends DataTable
{

    public function dataTable($query)
    {
        $query = MapKategoriBalans::select('map_kategori_balans.*', 'kategori_balans.kategori_balans', 'kategori_balans.kategori_balans_desc', 'material.material_name', 'version_asumsi.version')
            ->leftjoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'map_kategori_balans.version_id')
            ->leftjoin('material', 'material.material_code', '=', 'map_kategori_balans.material_code')
            ->leftjoin('company', 'company.company_code', '=', 'map_kategori_balans.company_code');
        return datatables()
            ->eloquent($query)
            ->addColumn('version', function ($query){
                return $query->version;
            })
            ->addColumn('material', function ($query){
                return $query->material_code.' - '.$query->material_name;
            })
            ->addColumn('kategori_balans', function ($query){
                return $query->kategori_balans.' - '.$query->kategori_balans_desc;
            })
            ->filterColumn('filter_material', function ($query, $keyword){
                if ($keyword != 'all'){
                    $query->where('map_kategori_balans.material_code', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_kategori_balans', function ($query, $keyword){
                if ($keyword != 'all'){
                    $query->where('kategori_balans.id', $keyword);
                }
            })
            ->filterColumn('filter_version', function ($query, $keyword){
                $query->where('version_asumsi.version', $keyword);
            })
            ->orderColumn('filter_material', function ($query, $order){
                $query->orderBy('map_kategori_balans.material_code', $order);
            })
            ->orderColumn('filter_kategori_balans', function ($query, $order){
                $query->orderBy('kategori_balans.kategori_balans', $order);
            })
            ->orderColumn('filter_version', function ($query, $order){
                $query->orderBy('version_asumsi.version', $order);
            })
            ->addColumn('action', 'pages.master.mapping_balans.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_map_kategori_balans')
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
        return 'Master\MapKategoriBalans_' . date('YmdHis');
    }
}
