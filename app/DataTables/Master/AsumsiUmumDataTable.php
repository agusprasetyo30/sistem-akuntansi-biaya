<?php

namespace App\DataTables\Master;

use App\Models\Asumsi_Umum;
use App\Models\Master\AsumsiUmum;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AsumsiUmumDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = Version_Asumsi::select('version_asumsi.*');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('asumsi_umum')->company_code == 'all') {
            $query = $query->where('version_asumsi.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('asumsi_umum')->company_code != 'all') {
            $query = $query->where('version_asumsi.company_code', auth()->user()->mapping_akses('asumsi_umum')->company_code);
        }

        if ($this->filter_version != 'all') {
            $query = $query->where('version_asumsi.id', $this->filter_version);
        }

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('c_version', function ($query) {
                return $query->version;
            })
            ->addColumn('c_data_bulan', function ($query) {
                $data = "<p>" . $query->data_bulan . " Bulan</p>";

                return $data;
            })
            ->addColumn('c_saldo_awal', function ($query) {
                return format_month($query->saldo_awal, 'bi');
            })
            ->addColumn('c_awal_periode', function ($query) {
                return format_month($query->awal_periode, 'bi');
            })
            ->addColumn('c_akhir_periode', function ($query) {
                return format_month($query->akhir_periode, 'bi');
            })

            ->filterColumn('filter_c_saldo_awal', function ($query, $keyword) {
                $temp = explode('/', $keyword);
                if (count($temp) == 1) {
                    $query->Where('saldo_awal', 'ilike', '%' . $keyword . '%');
                } elseif (count($temp) == 2) {
                    $keyword = $temp[1] . '-' . $temp[0];
                    $query->Where('saldo_awal', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_c_awal_periode', function ($query, $keyword) {
                $temp = explode('/', $keyword);
                if (count($temp) == 1) {
                    $query->Where('awal_periode', 'ilike', '%' . $keyword . '%');
                } elseif (count($temp) == 2) {
                    $keyword = $temp[1] . '-' . $temp[0];
                    $query->Where('awal_periode', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_c_akhir_periode', function ($query, $keyword) {
                $temp = explode('/', $keyword);
                if (count($temp) == 1) {
                    $query->Where('akhir_periode', 'ilike', '%' . $keyword . '%');
                } elseif (count($temp) == 2) {
                    $keyword = $temp[1] . '-' . $temp[0];
                    $query->Where('akhir_periode', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_bulan', function ($query, $keyword) {
                $keyword = strtolower($keyword);
                $keyword = str_replace(' bulan', '', $keyword);
                $query->where('data_bulan', 'ilike', '%' . $keyword . '%');
            })

            ->orderColumn('filter_c_saldo_awal', function ($query, $order) {
                $query->orderBy('saldo_awal', $order);
            })
            ->orderColumn('filter_c_awal_periode', function ($query, $order) {
                $query->orderBy('awal_periode', $order);
            })
            ->orderColumn('filter_c_akhir_periode', function ($query, $order) {
                $query->orderBy('akhir_periode', $order);
            })
            ->orderColumn('filter_bulan', function ($query, $order) {
                $query->orderBy('data_bulan', $order);
            })

            ->addColumn('action', 'pages.master.asumsi_umum.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('master\asumsiumum-table')
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
        return 'Master\AsumsiUmum_' . date('YmdHis');
    }
}
