<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TarifDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('tarif')
            ->select('tarif.*', 'material.material_name', 'plant.plant_desc', 'group_account_fc.group_account_fc_desc', 'version_asumsi.version')
            ->leftjoin('plant', 'plant.plant_code', '=', 'tarif.plant_code')
            ->leftJoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'tarif.group_account_fc')
            ->leftjoin('material', 'material.material_code', '=', 'tarif.product_code')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'tarif.version_id')
            ->whereNull('tarif.deleted_at');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('tarif')->company_code == 'all') {
            $query = $query->where('tarif.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('tarif')->company_code != 'all') {
            $query = $query->where('tarif.company_code', auth()->user()->mapping_akses('tarif')->company_code);
        }

        if ($this->filter_version != 'all') {
            $query = $query->where('tarif.version_id', $this->filter_version);
        }

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('plant', function ($query) {
                return $query->plant_code . ' ' . $query->plant_desc;
            })
            ->editColumn('group_account_fc', function ($query) {
                return $query->group_account_fc . ' ' . $query->group_account_fc_desc;
            })
            ->editColumn('produk', function ($query) {
                return $query->product_code . ' ' . $query->material_name;
            })
            ->orderColumn('filter_plant', function ($query, $order) {
                $query->orderBy('plant.plant_code', $order);
            })
            ->orderColumn('filter_group_account', function ($query, $order) {
                $query->orderBy('group_account_fc.group_account_fc', $order);
            })
            ->orderColumn('filter_produk', function ($query, $order) {
                $query->orderBy('material.material_code', $order);
            })
            ->orderColumn('filter_version', function ($query, $order) {
                $query->orderBy('version_asumsi.id', $order);
            })
            ->filterColumn('filter_plant', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('plant.plant_code', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_group_account_fc', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('group_account_fc.group_account_fc', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_material', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('material.material_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('material.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_version', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('version_asumsi.id', 'ilike', '%' . $keyword . '%')
                        ->orWhere('version_asumsi.version', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.master.tarif.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_tarif')
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
        return 'Master\Tarif_' . date('YmdHis');
    }
}
