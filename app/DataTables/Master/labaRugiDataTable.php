<?php

namespace App\DataTables\Master;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\LabaRugi;

class labaRugiDataTable extends DataTable
{

    public function dataTable($query)
    {
        $query = LabaRugi::select('laba_rugi.*', 'kategori_produk.kategori_produk_desc', 'kategori_produk.kategori_produk_name')
            ->leftjoin('kategori_produk', 'kategori_produk.id', '=', 'laba_rugi.kategori_produk_id');
        return datatables()
            ->eloquent($query)
            ->addColumn('periode', function ($query){
                return format_year($query->periode);
            })
            ->addColumn('kategori_produk', function ($query){
                return $query->kategori_produk_name. ' - '.$query->kategori_produk_desc ;
            })
            ->addColumn('biaya_penjualan', function ($query){
                return rupiah($query->value_bp);
            })
            ->addColumn('biaya_adm_umum', function ($query){
                return rupiah($query->value_bau);
            })
            ->addColumn('biaya_bunga', function ($query){
                return rupiah($query->value_bb);
            })
            ->filterColumn('filter_periode', function ($query, $keyword){
                $query->where('laba_rugi.periode', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_kategori_produk', function ($query, $keyword){
                $query->where('kategori_produk.kategori_produk_name', 'ilike', '%'.$keyword.'%')
                    ->where('kategori_produk.kategori_produk_desc', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_biaya_penjualan', function ($query, $keyword){
                $keyword = str_replace('.', '', str_replace('Rp ', '', $keyword));
                $query->where('laba_rugi.value_bp', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_biaya_adm_umum', function ($query, $keyword){
                $keyword = str_replace('.', '', str_replace('Rp ', '', $keyword));
                $query->where('laba_rugi.value_bau', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_biaya_bunga', function ($query, $keyword){
                $keyword = str_replace('.', '', str_replace('Rp ', '', $keyword));
                $query->where('laba_rugi.value_bb', 'ilike', '%'.$keyword.'%');
            })
            ->orderColumn('filter_periode', function ($query, $order) {
                $query->orderBy('laba_rugi.periode', $order);
            })
            ->orderColumn('filter_kategori_produk', function ($query, $order) {
                $query->orderBy('kategori_produk.kategori_produk_name', $order);
            })
            ->orderColumn('filter_biaya_penjualan', function ($query, $order) {
                $query->orderBy('laba_rugi.value_bp', $order);
            })
            ->orderColumn('filter_biaya_adm_umum', function ($query, $order) {
                $query->orderBy('laba_rugi.value_bau', $order);
            })
            ->orderColumn('filter_biaya_bunga', function ($query, $order) {
                $query->orderBy('laba_rugi.value_bb', $order);
            })
            ->addColumn('action', 'pages.buku_besar.laba_rugi.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_laba_rugi')
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
        return 'Master\labaRugi_' . date('YmdHis');
    }
}
