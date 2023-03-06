<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function PHPUnit\Framework\isEmpty;

class H_QtyRenProdDataTable extends DataTable
{
    public function dataTable($query)
    {
        $cc = auth()->user()->company_code;

        $query = DB::table('qty_renprod')
            ->select('qty_renprod.cost_center', 'cost_center.cost_center_desc')
            ->leftjoin('cost_center', 'cost_center.cost_center', '=', 'qty_renprod.cost_center')
            ->whereNull('qty_renprod.deleted_at')
            ->where('qty_renprod.version_id', $this->version)
            ->where('qty_renprod.company_code', $cc)
            ->groupBy('qty_renprod.cost_center', 'cost_center.cost_center_desc');

        $datatable = datatables()
            ->query($query)
            ->addColumn('cost_center', function ($query) {
                return $query->cost_center . ' ' . $query->cost_center_desc;
            });

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();

        $renprodValues = DB::table('qty_renprod')
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($renprodValues, $a) {
                $renprodAsumsi = $renprodValues
                    ->where('asumsi_umum_id', $a->id)
                    ->where('cost_center', $query->cost_center)
                    ->first();

                return $renprodAsumsi ? helpRibuan($renprodAsumsi->qty_renprod_value) : '-';
            });
        }

        return $datatable;
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('h_dt_qty_renprod')
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
        return 'Master\H_QtyRenProd_' . date('YmdHis');
    }
}
