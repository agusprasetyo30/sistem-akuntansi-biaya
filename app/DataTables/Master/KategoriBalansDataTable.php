<?php

namespace App\DataTables\Master;

use App\Models\KategoriBalans;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KategoriBalansDataTable extends DataTable
{

    public function dataTable($query)
    {
        $query = KategoriBalans::select('kategori_balans.*')
            ->leftjoin('company', 'company.company_code', 'kategori_balans.company_code')
            ->whereNull('kategori_balans.deleted_at');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('kategori_balans')->company_code == 'all') {
            $query = $query->where('kategori_balans.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('kategori_balans')->company_code != 'all') {
            $query = $query->where('kategori_balans.company_code', auth()->user()->mapping_akses('kategori_balans')->company_code);
        }

        return datatables()
            ->eloquent($query)
            ->addColumn('kategori_balans', function ($query) {
                return $query->kategori_balans;
            })
            ->addColumn('kategori_balans_desc', function ($query) {
                return $query->kategori_balans_desc;
            })
            ->addColumn('type_kategori', function ($query) {
                if ($query->type_kategori_balans == 'default') {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Default</span>";
                } elseif ($query->type_kategori_balans == 'produksi') {
                    $span = "<span class='badge bg-primary-light border-primary mt-2'>Produksi</span>";
                } else {
                    $span = "<span class='badge bg-info-light border-info mt-2'>Pemakaian</span>";
                }
                return $span;
            })
            ->addColumn('urutan', function ($query) {
                return $query->order_view;
            })
            ->filterColumn('filter_kategori_balans', function ($query, $keyword) {
                $query->where('kategori_balans.kategori_balans_desc', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_kategori_balans_desc', function ($query, $keyword) {
                $query->where('kategori_balans.kategori_balans', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_urutan', function ($query, $keyword) {
                $query->where('kategori_balans.order_view', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_type_kategori', function ($query, $keyword) {
                $query->where('kategori_balans.type_kategori_balans', 'ilike', '%' . $keyword . '%');
            })
            ->orderColumn('filter_kategori_balans', function ($query, $order) {
                $query->orderBy('kategori_balans.kategori_balans', $order);
            })
            ->orderColumn('filter_kategori_balans_desc', function ($query, $order) {
                $query->orderBy('kategori_balans.kategori_balans_desc', $order);
            })
            ->orderColumn('filter_urutan', function ($query, $order) {
                $query->orderBy('kategori_balans.order_view', $order);
            })
            ->orderColumn('filter_type_kategori', function ($query, $order) {
                $query->orderBy('kategori_balans.type_kategori_balans', $order);
            })
            ->addColumn('action', 'pages.master.kategori_balans.action')
            ->escapeColumns([]);
    }


    public function html()
    {
        return $this->builder()
            ->setTableId('dt_kategori_balans')
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
        return 'Master\KategoriBalans_' . date('YmdHis');
    }
}
