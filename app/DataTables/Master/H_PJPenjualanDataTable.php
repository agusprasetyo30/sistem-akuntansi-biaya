<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function PHPUnit\Framework\isEmpty;

class H_PJPenjualanDataTable extends DataTable
{
    public function dataTable($query)
    {
        $query = DB::table('pj_penjualan')
            ->select('pj_penjualan.material_code', 'material.material_name', 'material.material_uom')
            ->leftjoin('material', 'material.material_code', '=', 'pj_penjualan.material_code')
            ->whereNull('pj_penjualan.deleted_at')
            ->where('pj_penjualan.version_id', $this->version)
            ->groupBy('pj_penjualan.material_code', 'material.material_name', 'material.material_uom');

        if ($this->company != 'all' && auth()->user()->mapping_akses('pj_penjualan')->company_code == 'all') {
            $query = $query->where('pj_penjualan.company_code', $this->company);
        } else if ($this->company != 'all' && auth()->user()->mapping_akses('pj_penjualan')->company_code != 'all') {
            $query = $query->where('pj_penjualan.company_code', auth()->user()->mapping_akses('pj_penjualan')->company_code);
        }

        $datatable = datatables()
            ->query($query)
            ->addColumn('material_code', function ($query) {
                return $query->material_code . ' ' . $query->material_name;
            })
            ->addColumn('uom', function ($query) {
                return $query->material_uom;
            });;

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();

        $penjualanValues = DB::table('pj_penjualan')
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($penjualanValues, $a) {
                $penjualanAsumsi = $penjualanValues
                    ->where('asumsi_umum_id', $a->id)
                    ->where('material_code', $query->material_code)
                    ->first();

                return $penjualanAsumsi ? helpRibuan($penjualanAsumsi->pj_penjualan_value) : '-';
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
            ->setTableId('h_dt_pj_penjualan')
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
        return 'Master\H_Penjualan_' . date('YmdHis');
    }
}
