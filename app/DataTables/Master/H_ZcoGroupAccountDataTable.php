<?php

namespace App\DataTables\Master;

use App\Models\GroupAccount;
use App\Models\Material;
use App\Models\Zco;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class H_ZcoGroupAccountDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = GroupAccount::select('group_account_code', 'group_account_desc');
        if (auth()->user()->mapping_akses('zco')->company_code != 'all') {
            $query = $query->where('group_account.company_code', auth()->user()->mapping_akses('zco')->company_code);
        }

        $datatable = datatables()
            ->eloquent($query);

        $product = Zco::select('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name')
            ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
            ->leftjoin('plant', 'zco.plant_code', '=', 'plant.plant_code')
            ->groupBy('zco.product_code', 'zco.plant_code', 'plant.plant_desc', 'material.material_name');

        if (auth()->user()->mapping_akses('zco')->company_code != 'all') {
            $product = $product->where('zco.company_code', auth()->user()->mapping_akses('zco')->company_code);
        }

        if ($this->version) {
            $product = $product->where('zco.version_id', $this->version);
        }

        if ($this->material != 'all') {
            $product->where('zco.product_code', $this->material);
        }

        if ($this->plant != 'all') {
            $product->where('zco.plant_code', $this->plant);
        }

        // if ($this->format == '0') {
        //     $temp = explode('-', $this->moth);
        //     $timemonth = $temp[1] . '-' . $temp[0];

        //     $product->where('periode', 'ilike', '%' . $timemonth . '%');
        // } else if ($this->format == '1') {
        //     $start_temp = explode('-', $this->start_month);
        //     $end_temp = explode('-', $this->end_month);
        //     $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
        //     $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

        //     $product->whereBetween('periode', [$start_date, $end_date]);
        // }

        $product = $product->get();

        $zcoValues = DB::table('zco')
            ->select('zco.*', 'gl_account.group_account_code')
            ->leftjoin('gl_account', 'zco.cost_element', '=', 'gl_account.gl_account')
            ->whereIn('product_code', $product->pluck('product_code')->all())
            ->whereIn('plant_code', $product->pluck('plant_code')->all())
            ->whereIn('periode', $product->pluck('periode')->all())
            ->get();
        // dd($zcoValues);
        foreach ($product as $key => $item) {
            $datatable->addColumn($key, function ($query) use ($zcoValues, $item) {
                return '-';
            })->addColumn($key, function ($query) use ($zcoValues, $item) {
                return '-';
            })->addColumn($key, function ($query) use ($zcoValues, $item) {
                $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount', 'gl_account.group_account_code'))
                    ->leftjoin('gl_account', 'zco.cost_element', '=', 'gl_account.gl_account')
                    ->where([
                        'product_code' => $item->product_code,
                        'plant_code' => $item->plant_code,
                        'group_account_code' => $query->group_account_code,
                    ]);

                $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
                    ->where([
                        'product_code' => $item->product_code,
                        'plant_code' => $item->plant_code,
                    ])->groupBy('product_qty', 'periode');

                if ($this->format == '0') {
                    $total_biaya->where('periode', 'ilike', '%' . $this->moth . '%');
                    $kuantum_produksi->where('periode', 'ilike', '%' . $this->moth . '%');
                } else if ($this->format == '1') {
                    $start_temp = explode('-', $this->start_month);
                    $end_temp = explode('-', $this->end_month);
                    $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                    $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                    $total_biaya->whereBetween('periode', [$start_date, $end_date]);
                    $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
                }

                $total_biaya = $total_biaya->first();
                $kuantum_produksi = $kuantum_produksi->get()->toArray();

                $tot_kuanprod = 0;

                for ($i = 0; $i < count($kuantum_produksi); $i++) {
                    $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
                }

                $biaya_perton = 0;
                if ($total_biaya->total_amount > 0 && $tot_kuanprod > 0) {
                    $biaya_perton = $total_biaya->total_amount / $tot_kuanprod;
                }

                return $biaya_perton ? rupiah($biaya_perton) : '-';
            })->addColumn($key, function ($query) use ($zcoValues, $item) {
                $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount', 'gl_account.group_account_code'))
                    ->leftjoin('gl_account', 'zco.cost_element', '=', 'gl_account.gl_account')
                    ->where([
                        'product_code' => $item->product_code,
                        'plant_code' => $item->plant_code,
                        'group_account_code' => $query->group_account_code,
                    ]);

                if ($this->format == '0') {
                    $total_biaya->where('periode', 'ilike', '%' . $this->moth . '%');
                } else if ($this->format == '1') {
                    $start_temp = explode('-', $this->start_month);
                    $end_temp = explode('-', $this->end_month);
                    $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                    $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                    $total_biaya->whereBetween('periode', [$start_date, $end_date]);
                }

                $total_biaya = $total_biaya->first();

                return $total_biaya->total_amount ? rupiah($total_biaya->total_amount) : '-';
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
