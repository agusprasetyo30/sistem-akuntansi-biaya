<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function PHPUnit\Framework\isEmpty;

class H_PJPemakaianDataTable extends DataTable
{
    public function dataTable($query)
    {
        $cc = auth()->user()->company_code;

        $query = DB::table('pj_pemakaian')
            ->select('pj_pemakaian.material_code', 'material.material_name', 'material.material_uom')
            ->leftjoin('material', 'material.material_code', '=', 'pj_pemakaian.material_code')
            ->whereNull('pj_pemakaian.deleted_at')
            ->where('pj_pemakaian.version_id', $this->version)
            ->where('pj_pemakaian.company_code', $cc)
            ->groupBy('pj_pemakaian.material_code', 'material.material_name', 'material.material_uom');

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

        $pemakaianValues = DB::table('pj_pemakaian')
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($pemakaianValues, $a) {
                $pemakaianAsumsi = $pemakaianValues
                    ->where('asumsi_umum_id', $a->id)
                    ->where('material_code', $query->material_code)
                    ->first();

                return $pemakaianAsumsi ? helpRibuan($pemakaianAsumsi->pj_pemakaian_value) : '-';
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
            ->setTableId('h_dt_pj_pemakaian')
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
        return 'Master\H_Pemakaian_' . date('YmdHis');
    }
}
