<?php

namespace App\DataTables\Master;

use App\Models\KategoriProduk;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KategoriProdukDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = KategoriProduk::whereNull('deleted_at');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('kategori_produk')->company_code == 'all') {
            $query = $query->where('kategori_produk.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('kategori_produk')->company_code != 'all') {
            $query = $query->where('kategori_produk.company_code', auth()->user()->mapping_akses('kategori_produk')->company_code);
        }

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('status', function ($query) {
                if ($query->is_active == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Aktif</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak Aktif</span>";
                }

                return $span;
            })
            ->filterColumn('filter_status', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('is_active', true);
                    } elseif ($keyword == false) {
                        $query->where('is_active', false);
                    }
                }
            })
            ->addColumn('action', 'pages.master.kategori_produk.action')
            ->escapeColumns([]);
    }
}
