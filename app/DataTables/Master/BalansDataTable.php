<?php

namespace App\DataTables\Master;

use App\Models\Balans;
use App\Models\MapKategoriBalans;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class BalansDataTable extends DataTable
{
    public function dataTable()
    {
//        $query = MapKategoriBalans::select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code', 'kategori_balans.kategori_balans')
//            ->leftjoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
//            ->whereIn('map_kategori_balans.material_code', $this->material == 'all' ? $this->antrian : [$this->material])
//            ->where('map_kategori_balans.version_id', $this->version)
//            ->orderBy('map_kategori_balans.material_code', 'ASC')
//            ->orderBy('kategori_balans.order_view', 'ASC')->get();
//
//        dd($query);

            $query = Balans::
            select('kategori_balans_id','material_code', 'plant_code', 'company_code', 'kategori_balans_desc', 'order_view')
            ->whereIn('material_code', $this->material == 'all' ? $this->antrian : [$this->material])
            ->where('version_id', $this->version)
            ->groupBy('kategori_balans_id','material_code', 'plant_code', 'company_code', 'kategori_balans_desc', 'order_view')
            ->orderBy('material_code', 'ASC')
            ->orderBy('order_view', 'ASC');

//        dd($query);

        $datatable = datatables()
            ->eloquent($query)
            ->addColumn('material', function ($query){
                return $query->material_code;
            })
            ->addColumn('keterangan', function ($query){
                return $query->kategori_balans_desc;
            })
            ->addColumn('plant', function ($query){
                return $query->plant_code;
            });

        $main_asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)->get();

        $asumsi_balans = $main_asumsi->pluck('id')->all();

        $balans = DB::table('balans')
            ->select('balans.kategori_balans_id', 'balans.asumsi_umum_id', 'balans.company_code', 'balans.plant_code', 'balans.material_code', 'balans.q', 'balans.p', 'balans.nilai')
            ->where('balans.version_id', $this->version)
            ->get();


        foreach ($asumsi_balans as $key => $items){

            $datatable->addColumn('q'.$key, function ($query) use ($items, $key, $balans, $main_asumsi){
                $result = $balans->where('kategori_balans_id', $query->kategori_balans_id)
                    ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                    ->where('company_code', $query->company_code)
                    ->where('plant_code', $query->plant_code)
                    ->where('material_code', $query->material_code)
                    ->first();

                return handle_null($result, $result->q);
            })->addColumn('p'.$key, function ($query) use ($items, $key, $balans, $main_asumsi){
                $result = $balans->where('kategori_balans_id', $query->kategori_balans_id)
                    ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                    ->where('company_code', $query->company_code)
                    ->where('plant_code', $query->plant_code)
                    ->where('material_code', $query->material_code)
                    ->first();

                return rupiah(handle_null($result, $result->p));
            })->addColumn('nilai'.$key, function ($query) use ($items, $key, $balans, $main_asumsi){
                $result = $balans->where('kategori_balans_id', $query->kategori_balans_id)
                    ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                    ->where('company_code', $query->company_code)
                    ->where('plant_code', $query->plant_code)
                    ->where('material_code', $query->material_code)
                    ->first();

                return rupiah(handle_null($result, $result->nilai));
            });
        }

//        dd($datatable->toArray());

        return $datatable;

    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_balans')
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
        return 'Master\Balans_' . date('YmdHis');
    }
}
