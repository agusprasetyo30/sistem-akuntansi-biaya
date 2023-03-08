<?php

namespace App\DataTables\Master;

use App\Models\Master\KelengkapanHargaMaterial;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KelengkapanHargaMaterialDataTable extends DataTable
{

    public function dataTable($query)
    {
        $cons_rate = DB::table('cons_rate')
            ->select('material_code')
            ->where('month_year', 'ilike', '%'.$this->date.'%')
            ->where('version_id', '=', $this->versi)
            ->where('company_code', '=', $this->company)
            ->groupBy('material_code')->get()
            ->pluck('material_code')->all();


        $query = DB::table('price_rendaan')
            ->select('price_rendaan.material_code', 'material.material_name', 'price_rendaan.company_code', 'price_rendaan.region_name', 'regions.region_desc')
            ->leftJoin('material', 'material.material_code', '=', 'price_rendaan.material_code')
            ->leftJoin('regions', 'regions.region_name', '=', 'price_rendaan.region_name')
            ->where('price_rendaan.price_rendaan_value', '=', 0)
            ->whereIn('price_rendaan.asumsi_umum_id', $this->asumsi)
            ->where('price_rendaan.company_code', $this->company)
            ->whereIn('price_rendaan.material_code', $cons_rate);

        return datatables()
            ->query($query)
            ->addColumn('company', function ($query){
                return $query->company_code;
            })
            ->addColumn('material_data', function ($query){
                return $query->material_code. ' - '.$query->material_name;
            })
            ->addColumn('region', function ($query){
                return $query->region_name. ' - '.$query->region_desc;
            })
            ->filterColumn('filter_company', function ($query, $keyword){
                $query->where('price_rendaan.company_code', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_material_data', function ($query, $keyword){
                $query->where('price_rendaan.material_code', 'ilike', '%' . $keyword . '%')
                    ->orwhere('material.material_name', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_region', function ($query, $keyword){
                $query->where('price_rendaan.region_name', 'ilike', '%' . $keyword . '%')
                    ->orwhere('regions.region_desc', 'ilike', '%' . $keyword . '%');
            })
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_kelengkapan_harga_material')
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
        return 'Master\KelengkapanHargaMaterial_' . date('YmdHis');
    }
}
