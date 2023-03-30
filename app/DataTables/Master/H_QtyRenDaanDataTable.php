<?php

namespace App\DataTables\Master;

use App\Models\Master\H_QtyRenDaan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function PHPUnit\Framework\isEmpty;

class H_QtyRenDaanDataTable extends DataTable
{


    public function dataTable($query)
    {
        $query = DB::table('qty_rendaan')
            ->select('qty_rendaan.material_code', 'qty_rendaan.region_name', 'regions.region_desc', 'material.material_name', 'material.material_uom')
            ->leftjoin('material', 'material.material_code', '=', 'qty_rendaan.material_code')
            ->leftjoin('regions', 'regions.region_name', '=', 'qty_rendaan.region_name')
            ->whereNull('qty_rendaan.deleted_at')
            ->where('qty_rendaan.version_id', $this->version)
            ->groupBy('qty_rendaan.material_code', 'qty_rendaan.region_name', 'regions.region_desc', 'material.material_name', 'material.material_uom')
            ->orderBy('qty_rendaan.material_code', 'asc');

        if ($this->company != 'all' && auth()->user()->mapping_akses('qty_rendaan')->company_code == 'all') {
            $query = $query->where('qty_rendaan.company_code', $this->company);
        } else if ($this->company != 'all' && auth()->user()->mapping_akses('qty_rendaan')->company_code != 'all') {
            $query = $query->where('qty_rendaan.company_code', auth()->user()->mapping_akses('qty_rendaan')->company_code);
        }

        $datatable = datatables()
            ->query($query)
            ->addColumn('material', function ($query) {
                return $query->material_code . ' - ' . $query->material_name;
            })
            ->addColumn('uom', function ($query) {
                return $query->material_uom;
            });

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();
        $rendaanValues = DB::table('qty_rendaan')
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($rendaanValues, $a) {
                $rendaanAsumsi = $rendaanValues
                    ->where('asumsi_umum_id', $a->id)
                    ->where('region_name', $query->region_name)
                    ->where('material_code', $query->material_code)
                    ->first();

                return $rendaanAsumsi ? $rendaanAsumsi->qty_rendaan_value : '-';
            });
        }

        //        $data_value=[];
        //        foreach ($column['data'] as $key => $items){
        //            foreach ($asumsi as $key1 => $items1){
        //                $value = DB::table('qty_rendaan')
        //                    ->where([
        //                        'asumsi_umum_id' => $items1->id,
        //                        'region_id' => $items['region_id'],
        //                        'material_code' => $items['material_code'],
        //                    ])
        //                    ->first();
        //                $data = collect($value);
        //
        //                if ($data->isNotEmpty()){
        ////                    array_push($data_value, $data['qty_rendaan_value']);
        //                    $datatable->addColumn($key1, function ($query) use ($data,$key, $key1){
        //                        return $data['qty_rendaan_value'] ;
        //                    });
        //                }
        //                else{
        ////                    array_push($data_value, '-');
        //                    $datatable->addColumn($key1, function ($query) use ($data){
        //                        return '-';
        //                    });
        //                }
        //
        //            }
        //        }
        //        $data = array_chunk($data_value, count($asumsi));
        //
        //        foreach ($data as $key2=> $items2){
        //            foreach ($items2 as $key3 => $items3){
        //                $datatable->toArray()['data'][$key2]['value'.$key3] = $items3;
        //            }
        //
        //        }
        //
        //        dd($datatable->toArray()['data']);
        return $datatable;
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('h_dt_qty_rendaan')
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
        return 'Master\H_QtyRenDaan_' . date('YmdHis');
    }
}
