<?php

namespace App\DataTables\Master;

use App\Models\Asumsi_Umum;
use App\Models\CostCenter;
use App\Models\GroupAccountFC;
use App\Models\Master\H_Salr;
use App\Models\Salr;
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


        $data_inflasi = Asumsi_Umum::where('id', $this->inflasi_asumsi)
            ->first();

        if ($this->cost_center != 'all'){
            $cost_center->where('salrs.cost_center', $this->cost_center);
        }

        $cost_center = $cost_center->get();

        foreach ($cost_center as $key => $item){
            $datatable->addColumn($key, function ($query) use ($item, $data_inflasi){
                $value_salr = Salr::select(DB::raw('SUM(value) as value'))
                    ->where([
                        'cost_center' => $item->cost_center,
                        'group_account_fc' => $query-> group_account_fc
                    ]);

                // Periode
                if ($this->format == '0'){
                    $value_salr->where('periode', 'ilike', '%'.$this->year.'%');
                }elseif ($this->format == '1'){
                    $temp = explode('-', $this->moth);
                    $timemonth = $temp[1].'-'.$temp[0];

                    $value_salr->where('periode', 'ilike', '%'.$timemonth.'%');
                }elseif ($this->format == '2'){
                    $start_temp = explode('-', $this->start_month);
                    $end_temp = explode('-', $this->end_month);
                    $start_date = $start_temp[1].'-'.$start_temp[0].'-01 00:00:00';
                    $end_date = $end_temp[1].'-'.$end_temp[0].'-01 00:00:00';

                    $value_salr->whereBetween('periode', [$start_date, $end_date]);
                }

                $value_salr = $value_salr->first();

                // Inflasi
                if ($this->inflasi == '1'){
                    $result = $value_salr->value * $data_inflasi->inflasi / 100;
                }else{
                    $result = $value_salr->value;
                }

                return $value_salr->value != null ? rupiah($result):'-';
            });
        }

        return $datatable;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('master\h_salr-table')
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
        return 'Master\H_Salr_' . date('YmdHis');
    }
}