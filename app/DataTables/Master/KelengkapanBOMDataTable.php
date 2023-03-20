<?php

namespace App\DataTables\Master;

use App\Models\ConsRate;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KelengkapanBOMDataTable extends DataTable
{

    public function dataTable($query)
    {

        $query = DB::table('cons_rate as cr')
            ->select(
                'cr.company_code',
                'cr.material_code',
                'cr.product_code',
                'cr.plant_code',
                'b.cr',
                'material.material_name',
                'plant.plant_desc',
                'cr.month_year',
                DB::raw('sum(cr.cons_rate) as sumcr')
            )
            ->leftJoin(DB::raw("(SELECT A.code, A.cr, A.product_code, A.plant_code, A.asumsi_umum_id  FROM simulasi_proyeksi A GROUP BY A.code, A.cr, A.product_code, A.plant_code, A.asumsi_umum_id) b"), function ($query){
                $query
                    ->on('b.code', '=', 'cr.material_code')
                    ->on('b.product_code', '=', 'cr.product_code')
                    ->on('b.plant_code', '=', 'cr.plant_code');
            })
            ->leftJoin('material', 'material.material_code', '=', 'cr.material_code')
            ->leftJoin('plant', 'plant.plant_code', '=', 'cr.plant_code')
            ->where('cr.version_id', '=', $this->versi)
            ->where('b.asumsi_umum_id', '=', $this->asumsi)
            ->where('cr.month_year', '=', $this->date)
            ->groupBy(
                'cr.company_code',
                'cr.material_code',
                'cr.product_code',
                'cr.plant_code',
                'b.cr',
                'material.material_name',
                'plant.plant_desc',
                'cr.month_year'
            )
            ->havingRaw('sum(cr.cons_rate) != b.cr');

//        dd($query->get()->toArray());
        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('company', function ($query){
                return $query->company_code;
            })
            ->addColumn('plant_data', function ($query){
                return $query->plant_code.' - '.$query->plant_desc;
            })
            ->addColumn('material_data', function ($query){
                return $query->material_code.' - '.$query->material_name;
            })
            ->filterColumn('filter_company', function ($query, $keyword){
                $query->where('cr.company_code', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_plant_data', function ($query, $keyword){
                $query->where('cr.plant_code', 'ilike', '%' . $keyword . '%')
                    ->orwhere('plant.plant_desc', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_material_data', function ($query, $keyword){
                $query->where('cr.material_code', 'ilike', '%' . $keyword . '%')
                    ->orwhere('material.material_name', 'ilike', '%' . $keyword . '%');
            })
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('master\kelengkapanbom-table')
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
        return 'Master\KelengkapanBOM_' . date('YmdHis');
    }
}
