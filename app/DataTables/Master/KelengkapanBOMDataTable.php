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

//        $query = DB::table('cons_rate')
//            ->leftJoin('simulasi_proyeksi', function ($query){
//                $query
//                    ->on('simulasi_proyeksi.code', '=', 'cons_rate.material_code')
//                    ->on('simulasi_proyeksi.product_code', '=', 'cons_rate.product_code')
//                    ->on('simulasi_proyeksi.plant_code', '=', 'cons_rate.plant_code');
//            })
//            ->select(
//                'cons_rate.material_code',
//                'cons_rate.product_code',
//                'cons_rate.plant_code',
//                'cons_rate.cons_rate',
//                'cons_rate.id',
//                DB::raw('sum(cons_rate.cons_rate) as cons_rate'),
////                DB::raw('count(cons_rate.cons_rate) as cons_rate_count'),
//                'simulasi_proyeksi.cr'
//            )
//
//            ->where('cons_rate.version_id', 3)
//            ->where('simulasi_proyeksi.cr', '200002')
////            ->where('simulasi_proyeksi.version_id',3)
//            ->groupBy(
//                'cons_rate.cons_rate',
//                'cons_rate.material_code',
//                'simulasi_proyeksi.cr',
////                'simulasi_proyeksi.code',
//                'cons_rate.id',
//                'cons_rate.product_code',
//                'cons_rate.plant_code',
//            );
//        $sub =

//        $query = ConsRate::with(['simulasi_proyeksi'])
//            ->select(
//                'cons_rate.material_code',
//                'cons_rate.product_code',
//                'cons_rate.plant_code',
//                DB::raw('sum(cons_rate.cons_rate) as cons_rate'),
//            )
//            ->where('cons_rate.version_id', 3)
////            ->where('simulasi_proyeksi.cr', '=', 'cons_rate')
//            ->groupBy(
//                'cons_rate.material_code',
//                'cons_rate.product_code',
//                'cons_rate.plant_code',
//            );


//        $query = DB::table('cons_rate')
//            ->select(
//                'cons_rate.material_code',
//                'cons_rate.product_code',
//                'cons_rate.plant_code',
//                DB::raw('sum(cons_rate.cons_rate) as cons_rate'),
//            )
////            ->where('cons_rate.version_id', 3)
//            ->where(function (Builder $query){
//                $query->from('simulasi_proyeksi')
//                    ->select(DB::raw("cr :: varchar"))
////                    ->select('simulasi_proyeksi.cr')
////                    ->select('cr as data')
//                    ->where('cons_rate', '=', '0.112')
////                    ->whereRaw("cons_rate.material_code = '4001255'")
////                    ->where(DB::raw("cons_rate :: varchar"), '=', 'simulasi_proyeksi.cr')
////                    ->where(DB::raw("cons_rate :: varchar"), '=', '7.4844')
////                    ->whereRaw("cons_rate = '0.016'")
//                    ->limit(1);
//            }, 'data')
//            ->groupBy(
//                'cons_rate.material_code',
//                'cons_rate.product_code',
//                'cons_rate.plant_code',
//            );


//
//        $query = DB::table('cons_rate as cr')
//            ->join(DB::raw("(SELECT A.code, A.cr FROM simulasi_proyeksi A GROUP BY A.code, A.cr) b"), 'b.code', '=', 'cr.material_code')
//            ->selectRaw("cr.material_code, cr.product_code, cr.plant_code, b.cr, SUM ( cr.cons_rate ) AS sumcr ")
//            ->groupBy('cr.material_code', 'cr.product_code', 'cr.plant_code','b.cr');

        $query = DB::table('cons_rate as cr')
            ->select(
                'cr.company_code',
                'cr.material_code',
                'cr.product_code',
                'cr.plant_code',
                'b.cr',
                'material.material_name',
                'plant.plant_desc',
//                DB::raw('sum(cr.cons_rate) as sumcr')
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
            ->groupBy(
                'cr.company_code',
                'cr.material_code',
                'cr.product_code',
                'cr.plant_code',
                'b.cr',
                'material.material_name',
                'plant.plant_desc',
            )
            ->havingRaw('sum(cr.cons_rate) != b.cr');
//        $hasil  = DB::table('simulasi_proyeksi')->fromSub($query1, 'simulasi_proyeksi')
//            ->join('cons_rate as a', 'a.material_code', '=', 'simulasi_proyeksi.material_code')
//            ->selectRaw(" A .material_code, A.product_code, A.plant_code, A.cons_rate AS mentah, simulasi_proyeksi.sumcr AS mateng,
//				simulasi_proyeksi.cr AS simulasi")
//            ->where(DB::raw("simulasi_proyeksi.sumcr :: varchar"), '!=', DB::raw("simulasi_proyeksi.cr :: varchar"))
//            ->get();

//        $data = $query->get();
//dd($query->get());
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
