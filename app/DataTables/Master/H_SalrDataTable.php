<?php

namespace App\DataTables\Master;

use App\Models\Asumsi_Umum;
use App\Models\CostCenter;
use App\Models\GroupAccountFC;
use App\Models\Master\H_Salr;
use App\Models\Salr;
use App\Models\Version_Asumsi;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class H_SalrDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = GroupAccountFC::select('group_account_fc', 'group_account_fc_desc');
        $datatable = datatables()
            ->eloquent($query);

        $cost_center = Salr::select('salrs.cost_center', 'cost_center.cost_center_desc')
            ->leftjoin('cost_center', 'salrs.cost_center', '=', 'cost_center.cost_center')
            ->groupBy('salrs.cost_center', 'cost_center.cost_center_desc');

        // Periode
        if ($this->format == '0'){
            $cost_center->where('salrs.version_id', $this->version);
        }elseif ($this->format == '1'){
            $cost_center->where('salrs.periode', 'ilike', '%-'.check_month_by_name($this->month).'-%')
                ->where('version_id', $this->version);

        }elseif ($this->format == '2'){
            $start_month = '2000-'.check_month_by_name($this->start_month).'-01 00:00:00';
            $end_month = '2000-'.check_month_by_name($this->end_month).'-01 00:00:00';
            $cost_center->whereBetween('salrs.periode', [$start_month, $end_month])
                ->where('version_id', $this->version);
        }

        $data_inflasi = Asumsi_Umum::where('month_year', 'ilike', '%-'.check_month_by_name($this->month).'-%')
            ->where('version_id', $this->version)
            ->first();

        if ($this->cost_center != 'all'){
            $cost_center->where('salrs.cost_center', $this->cost_center);
        }

        $cost_center = $cost_center->get();

        foreach ($cost_center as $key => $item){
            $datatable->addColumn($key, function ($query) use ($item, $data_inflasi){
                $value_salr = Salr::select(DB::raw('SUM(value) as value'))
                    ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                    ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                    ->where([
                        'salrs.cost_center' => $item->cost_center,
                        'group_account_fc.group_account_fc' => $query-> group_account_fc
                    ]);

                // Periode
                if ($this->format == '0'){
                    $value_salr->where('salrs.version_id', $this->version);
                }elseif ($this->format == '1'){
                    $value_salr->where('salrs.periode', 'ilike', '%-'.check_month_by_name($this->month).'-%')
                        ->where('version_id', $this->version);
                }elseif ($this->format == '2'){
                    $start_month = '2000-'.check_month_by_name($this->start_month).'-01 00:00:00';
                    $end_month = '2000-'.check_month_by_name($this->end_month).'-01 00:00:00';

                    $value_salr->whereBetween('salrs.periode', [$start_month, $end_month])
                        ->where('version_id', $this->version);
                }

                $value_salr = $value_salr->first();

                // Inflasi
                if ($this->inflasi == '1'){
//                    dd($value_salr->value, $data_inflasi->inflasi / 100);
                    $result = $value_salr->value * $data_inflasi->inflasi / 100;
                }else{
//                    dd($value_salr->value);
                    $result = $value_salr->value;
                }
                return $value_salr->value != null ? rupiah($result):'-';
            })->with('total'.$key , function () use ($item, $data_inflasi){
                $total = Salr::select(DB::raw('SUM(value) as value'))
                    ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                    ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                    ->where([
                        'salrs.cost_center' => $item->cost_center,
//                        'group_account_fc.group_account_fc' => $query-> group_account_fc
                    ]);

                // Periode
                if ($this->format == '0'){
                    $total->where('salrs.version_id', $this->version);
                }elseif ($this->format == '1'){
                    $total->where('salrs.periode', 'ilike', '%-'.check_month_by_name($this->month).'-%')
                        ->where('version_id', $this->version);
                }elseif ($this->format == '2'){

                    $start_month = '2000-'.check_month_by_name($this->start_month).'-01 00:00:00';
                    $end_month = '2000-'.check_month_by_name($this->end_month).'-01 00:00:00';

                    $total->whereBetween('salrs.periode', [$start_month, $end_month])
                        ->where('version_id', $this->version);
                }

                $total = $total->first();


                // Inflasi
                if ($this->inflasi == '1'){
                    $result = $total->value * $data_inflasi->inflasi / 100;
                }else{
                    $result = $total->value;
                }

                return $total->value != null ? rupiah($result):'-';
            });
        }


//        dd($datatable->toArray());

        return $datatable;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('master\h_salr-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
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
        return 'Master\H_Salr_' . date('YmdHis');
    }
}
