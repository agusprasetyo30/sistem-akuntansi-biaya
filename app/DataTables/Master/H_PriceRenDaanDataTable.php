<?php

namespace App\DataTables\Master;

use App\Models\Master\H_PriceRenDaan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class H_PriceRenDaanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('price_rendaan')
            ->select('price_rendaan.material_code', 'price_rendaan.region_name', 'regions.region_desc', 'material.material_name')
            ->leftjoin('material', 'material.material_code', '=', 'price_rendaan.material_code')
            ->leftjoin('regions', 'regions.region_name', '=', 'price_rendaan.region_name')
            ->whereNull('price_rendaan.deleted_at')
            ->where('price_rendaan.version_id', $this->version)
            ->groupBy('price_rendaan.material_code', 'price_rendaan.region_name', 'regions.region_desc', 'material.material_name');

        $datatable = datatables()
            ->query($query)
            ->addColumn('material', function ($query){
                return $query->material_code.' - '.$query->material_name;
            });

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();
        $pricerendaanValues = DB::table('price_rendaan')
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($pricerendaanValues, $a) {
                $pricerendaanAsumsi = $pricerendaanValues
                    ->where('asumsi_umum_id', $a->id)
                    ->where('region_name', $query->region_name)
                    ->where('material_code', $query->material_code)
                    ->first();

                return $pricerendaanAsumsi ? rupiah($pricerendaanAsumsi->price_rendaan_value) : '-';
            });
        }

        return $datatable;
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('h_dt_price_rendaan')
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
        return 'Master\H_PriceRenDaan_' . date('YmdHis');
    }
}
