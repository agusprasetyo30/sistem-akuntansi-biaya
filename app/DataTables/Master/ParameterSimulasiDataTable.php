<?php

namespace App\DataTables\Master;

use App\Models\Feature;
use App\Models\Master\ParameterSimulasi;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ParameterSimulasiDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $data = [
            'cons_rate',
            'asumsi_umum',
            'saldo_awal',
            'qty_renprod',
            'price_rendaan',
            'qty_rendaan',
            'kurs',
            'zco',
            'salrs',
            'pj_pemakaian',
            'pj_penjualan',
            'laba_rugi',
            'tarif',
        ];
        $query = Feature::with(['kurs', 'asumsi_umum', 'cons_rate', 'saldo_awal', 'qty_renprod', 'qty_rendaan', 'price_rendaan', 'zco', 'salr', 'labarugi', 'pemakaian', 'penjualan', 'tarif'])
            ->whereIn('db', $data);

        $datatable = datatables()
            ->eloquent($query);

        return $datatable;
    }

    public function html()
    {
        return $this->builder()
                    ->setTableId('master\parametersimulasi-table')
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
        return 'Master\ParameterSimulasi_' . date('YmdHis');
    }
}
