<?php

namespace App\DataTables\Master;

use App\Models\Material;
use App\Models\Zco;
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

        $query = Material::select('material_code', 'material_name', 'group_account_code')
            ->where('company_code', $cc);

        $datatable = datatables()
            ->eloquent($query);

        $product = Zco::select('zco.product_code', 'zco.plant_code', 'material.material_name')
            ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
            ->groupBy('zco.product_code', 'zco.plant_code', 'material.material_name');

        if ($this->material != 'all') {
            $product->where('zco.product_code', $this->material);
        }

        if ($this->plant != 'all') {
            $product->where('zco.plant_code', $this->plant);
        }

        $product = $product->get();
        $zcoValues = DB::table('zco')
            ->select('zco.*', 'gl_account.group_account_code')
            ->leftjoin('gl_account', 'zco.cost_element', '=', 'gl_account.gl_account')
            ->whereIn('product_code', $product->pluck('product_code')->all())
            ->whereIn('plant_code', $product->pluck('plant_code')->all())
            ->get();

        foreach ($product as $key => $item) {
            $datatable->addColumn($key, function ($query) use ($zcoValues, $item) {
                $total_qty = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_qty');

                $total_biaya = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_amount');

                $kuantum_produksi = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
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
            })->addColumn($key, function ($query) use ($zcoValues, $item) {
                $total_qty = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_qty');

                $kuantum_produksi = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
                    ->sum('product_qty');

                $cr = 0;
                if ($total_qty > 0 && $kuantum_produksi > 0) {
                    $cr = $total_qty / $kuantum_produksi;
                }

                return $cr ? round($cr, 2) : '-';
            })->addColumn($key, function ($query) use ($zcoValues, $item) {
                $total_biaya = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
                    ->where('material_code', $query->material_code)
                    ->sum('total_amount');

                $kuantum_produksi = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
                    ->sum('product_qty');

                $biaya_perton = 0;

                if ($total_biaya > 0 && $kuantum_produksi > 0) {
                    $biaya_perton = $total_biaya / $kuantum_produksi;
                }

                return $biaya_perton ? round($biaya_perton, 2) : '-';
            })->addColumn($key, function ($query) use ($zcoValues, $item) {
                $total_biaya = $zcoValues
                    ->where('product_code', $item->product_code)
                    ->where('plant_code', $item->plant_code)
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
