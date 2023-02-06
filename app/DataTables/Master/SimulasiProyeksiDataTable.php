<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function PHPUnit\Framework\isEmpty;

class SimulasiProyeksiDataTable extends DataTable
{
    public function dataTable($query)
    {
        $cr = DB::table("cons_rate")
            ->select(
                DB::raw("(
                    CASE
                        WHEN material.kategori_material_id = 1 THEN 1
                        WHEN material.kategori_material_id = 2 THEN 3
                        WHEN material.kategori_material_id = 3 THEN 2
                        WHEN material.kategori_material_id = 4 THEN 4
                        ELSE 0 END)
                    AS no"),
                "material.kategori_material_id as kategori",
                "material.material_name as name",
                "cons_rate.material_code as code",
            )
            ->leftJoin('material', 'material.material_code', '=', 'cons_rate.material_code');

        $salr = DB::table("salrs")
            ->select(
                DB::raw("(
                    CASE
                        WHEN group_account_fc.group_account_fc = '1200' OR
                        group_account_fc.group_account_fc = '1500' OR
                        group_account_fc.group_account_fc = '1100' OR
                        group_account_fc.group_account_fc = '1300' OR
                        group_account_fc.group_account_fc = '1600' OR
                        group_account_fc.group_account_fc = '1000' OR
                        group_account_fc.group_account_fc = '1400' THEN 8
                        ELSE 6 END)
                    AS no"),
                DB::raw("(
                    CASE
                        WHEN group_account_fc.group_account_fc IS NOT NULL THEN 1
                        ELSE 0 END)
                    AS kategori"),
                "group_account_fc.group_account_fc_desc as name",
                "group_account_fc.group_account_fc as code",
            )
            ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
            ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
            ->union($cr);

        $query = DB::table("temp_proyeksi")
            ->select(
                "temp_proyeksi.id as no",
                DB::raw("(
                    CASE
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar Balans' THEN 1
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar ZCOHPPDET' THEN 2
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar Stock' THEN 3
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar Saldo Awal & CR Sesuai Perhitungan' THEN 4
                        ELSE 0 END)
                    AS kategori"),
                "temp_proyeksi.proyeksi_name as name",
                "temp_proyeksi.proyeksi_name as code",
            )
            ->union($salr)
            ->orderBy('no', 'asc')
            ->orderBy('kategori', 'asc');

        $datatable = datatables()
            ->query($query)
            ->addColumn('name', function ($query) {
                return $query->name;
            });

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($a) {
                return '-';
            })->addColumn($key, function ($query) use ($a) {
                return '-';
            })->addColumn($key, function ($query) use ($a) {
                return '-';
            })->addColumn($key, function ($query) use ($a) {
                return '-';
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
            ->setTableId('h_dt_simulasi_proyeksi')
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
        return 'Master\H_SimulasiProyeksi_' . date('YmdHis');
    }
}
