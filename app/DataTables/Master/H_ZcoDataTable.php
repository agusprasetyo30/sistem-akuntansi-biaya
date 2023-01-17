<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class H_ZcoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $cc = auth()->user()->company_code;

        $query = DB::table('zco')
            ->select('zco.material_code', 'material.material_name')
            ->leftjoin('material', 'material.material_code', '=', 'zco.material_code')
            ->whereNull('zco.deleted_at')
            ->where('zco.company_code', $cc)
            ->groupBy('zco.material_code', 'material.material_name');

        $datatable = datatables()
            ->query($query)
            ->addColumn('material_code', function ($query) {
                return $query->material_code . ' ' . $query->material_name;
            });

        if ($this->material != 'all') {
            $mat = DB::table('material')
                ->where('material_code', $this->material)
                ->get();
        } else {
            $mat = DB::table('material')
                ->get();
        }

        $zcoValues = DB::table('zco')
            ->select('zco.*', 'material.group_account_code as ga_zco')
            ->leftjoin('material', 'material.material_code', '=', 'zco.material_code')
            ->whereIn('product_code', $mat->pluck('material_code')->all())
            ->get();

        foreach ($mat as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($zcoValues, $a) {
                $total_qty = $zcoValues
                    // ->where('ga_zco', $a->group_account_code)
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_qty');

                $total_biaya = $zcoValues
                    // ->where('ga_zco', $a->group_account_code)
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_amount');

                $kuantum_produksi = $zcoValues
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->sum('product_qty');

                $biaya_perton = 0;
                if ($total_biaya > 0 && $kuantum_produksi > 0) {
                    $biaya_perton = $total_biaya / $kuantum_produksi;
                }

                $cr = 0;
                if ($total_qty > 0 && $kuantum_produksi > 0) {
                    $cr = $total_qty / $kuantum_produksi;
                }

                $harga_satuan = 0;
                if ($biaya_perton > 0 && $cr > 0) {
                    $harga_satuan = $biaya_perton / $cr;
                }
                return $harga_satuan ? round($harga_satuan, 2)  : '-';
            })->addColumn($key, function ($query) use ($zcoValues, $a) {
                $total_qty = $zcoValues
                    // ->where('ga_zco', $a->group_account_code)
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_qty');

                $kuantum_produksi = $zcoValues
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->sum('product_qty');

                $cr = 0;
                if ($total_qty > 0 && $kuantum_produksi > 0) {
                    $cr = $total_qty / $kuantum_produksi;
                }

                return $cr ? round($cr, 2) : '-';
            })->addColumn($key, function ($query) use ($zcoValues, $a) {
                $total_biaya = $zcoValues
                    // ->where('ga_zco', $a->group_account_code)
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_amount');

                $kuantum_produksi = $zcoValues
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->sum('product_qty');

                $biaya_perton = 0;

                if ($total_biaya > 0 && $kuantum_produksi > 0) {
                    $biaya_perton = $total_biaya / $kuantum_produksi;
                }

                return $biaya_perton ? round($biaya_perton, 2) : '-';
            })->addColumn($key, function ($query) use ($zcoValues, $a) {
                $total_biaya = $zcoValues
                    // ->where('ga_zco', $a->group_account_code)
                    // ->where('plant_code', 'B001')
                    ->where('product_code', $a->material_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_amount');

                return $total_biaya ? round($total_biaya, 2) : '-';
            });
        }

        // dd($datatable->toArray());

        return $datatable;
    }

    // public function html()
    // {
    //     return $this->builder()
    //         ->addTableClass('table table-bordered text-nowrap key-buttons')
    //         ->setTableId('dt_qty_renprod')
    //         ->columns($this->getColumns())
    //         ->minifiedAjax()
    //         ->dom('Bfrtip')
    //         ->orderBy(1)
    //         ->buttons(
    //             Button::make('create'),
    //             Button::make('export'),
    //             Button::make('print'),
    //             Button::make('reset'),
    //             Button::make('reload')
    //         );
    // }

    /**
     * Get columns.
     *
     * @return array
     */
    // protected function getColumns()
    // {
    //     return [
    //         Column::computed('action')
    //             ->exportable(false)
    //             ->printable(false)
    //             ->width(60)
    //             ->addClass('text-center'),
    //         Column::make('id'),
    //         Column::make('add your columns'),
    //         Column::make('created_at'),
    //         Column::make('updated_at'),
    //     ];
    // }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'BukuBesar\H_ZCO_' . date('YmdHis');
    }
}
