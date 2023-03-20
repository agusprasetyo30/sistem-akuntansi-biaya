<?php

namespace App\DataTables\Master;

use App\Models\Asumsi_Umum;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ParameterSimulasiDataTable extends DataTable
{

    public function dataTable($query)
    {
        $data = [
            'kurs',
            'asumsi_umum',
            'cons_rate',
            'saldo_awal',
            'qty_renprod',
            'price_rendaan',
            'qty_rendaan',
            'zco',
            'salrs',
            'pj_pemakaian',
            'pj_penjualan',
            'laba_rugi',
            'tarif',
        ];

        $query = Feature::with(['kurs' =>function($query) {
            $query->where('month_year', 'ilike', '%'.$this->date.'%');
        }, 'asumsi_umum' =>function($query){
            $query->whereIn('id', $this->asumsi)
                ->where('company_code', $this->company);
        }, 'cons_rate' =>function($query){
            $query->where('month_year', 'ilike', '%'.$this->date.'%')
                ->where('version_id', $this->versi)
                ->where('company_code', $this->company);
        }, 'saldo_awal' =>function($query){
            $query->where('version_id', $this->versi)
                ->where('company_code', $this->company);
        }, 'qty_renprod' =>function($query){
            $query->whereIn('asumsi_umum_id', $this->asumsi)
                ->where('company_code', $this->company);
        }, 'qty_rendaan' =>function($query){
            $query->whereIn('asumsi_umum_id', $this->asumsi)
                ->where('company_code', $this->company);
        }, 'price_rendaan' =>function($query){
            $query->whereIn('asumsi_umum_id', $this->asumsi)
                ->where('company_code', $this->company);
        }, 'zco' =>function($query){
            $query->where('periode', 'ilike', '%'.$this->date.'%')
                ->where('company_code', $this->company);
        }, 'salr' =>function($query){
            $query->where('periode', 'ilike', '%'.$this->date.'%')
                ->where('company_code', $this->company);
        }, 'laba_rugi' =>function($query){
            $query->where('periode', 'ilike', '%'.$this->date.'%')
                ->where('company_code', $this->company);
        }, 'pj_pemakaian' =>function($query){
            $query->whereIn('asumsi_umum_id', $this->asumsi)
                ->where('company_code', $this->company);
        }, 'pj_penjualan' =>function($query){
            $query->whereIn('asumsi_umum_id', $this->asumsi)
                ->where('company_code', $this->company);
        }, 'tarif' =>function($query){
            $query->where('company_code', $this->company);
        }])
            ->whereIn('db', $data);

//        dd($query->get()->toArray());

        $datatable = datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('jumlah_feature', function ($query){
////                $data = $query->db.'_count';
                $data = $query[$query->db];
                if ($data == null){
                    return 0;
                }else{
                    return count($data);
                }
            })
            ->addColumn('data_db', function ($query){
                return $query->feature;
            })
            ->addColumn('status_data', function ($query){
//                if ($query->db == 'kurs'){
//                    $result = '-';
//                }else{
//                    if ($query[$query->db][0]->submited_at != null){
//
//                    }elseif ($query[$query->db][0]->submited_at != null){
//
//                    }elseif ($query[$query->db][0]->submited_at != null){
//
//                    }
//                }

                $result = "<span class='badge bg-secondary-light border-secondary fs-11 mt-2'>DRAFT</span>";

                return $result;
            })
            ->filterColumn('filter_data_db', function ($query, $keyword) {
                $query->where('feature', 'ilike', '%' . $keyword . '%');
            })
            ->escapeColumns([]);
//        dd($datatable->toArray());
        return $datatable;
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_parameter_simulasi')
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
        return 'Master\ParameterSimulasi_' . date('YmdHis');
    }
}
