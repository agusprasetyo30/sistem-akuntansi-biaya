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

class H_TotalDaanDataTable extends DataTable
{
    public function dataTable($query)
    {
        $query = DB::table('qty_rendaan')
            ->select('qty_rendaan.material_code', 'qty_rendaan.region_name', 'regions.region_desc', 'material.material_name')
            ->leftjoin('material', 'material.material_code', '=', 'qty_rendaan.material_code')
            ->leftjoin('regions', 'regions.region_name', '=', 'qty_rendaan.region_name')
            ->whereNull('qty_rendaan.deleted_at')
            ->where('qty_rendaan.version_id', $this->version)
            ->groupBy('qty_rendaan.material_code', 'qty_rendaan.region_name', 'regions.region_desc', 'material.material_name')
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
            });

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();

        $rendaanValues = DB::table('qty_rendaan')
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($rendaanValues, $a) {
                // $rendaanAsumsi = $rendaanValues
                //     ->where('asumsi_umum_id', $a->id)
                //     ->where('region_id', $query->region_id)
                //     ->where('material_code', $query->material_code)
                //     ->first();

                // $result = 0;
                // $cc_ = auth()->user()->company_code;

                // $query2 = DB::table('price_rendaan')
                //     ->select('price_rendaan.price_rendaan_value')
                //     ->where('material_code', $query->material_code)
                //     ->where('region_id', $query->region_id)
                //     ->where('version_id', $this->version)
                //     ->where('asumsi_umum_id', $a->id)
                //     ->whereNull('price_rendaan.deleted_at')
                //     ->where('price_rendaan.company_code', $cc_)
                //     ->first();

                // $val_qty_rendaan = $rendaanAsumsi ? $rendaanAsumsi->qty_rendaan_value : 0;
                // $val_price_daan = $query2 ? $query2->price_rendaan_value : 0;
                // $val_adjustment = $rendaanAsumsi ? $rendaanAsumsi->adjustment : 0;
                // $val_kurs = $rendaanAsumsi ? $rendaanAsumsi->usd_rate : 0;

                // if ($val_qty_rendaan > 0 && $val_price_daan == 0) {
                //     return '-';
                // } else {
                //     $result = $val_qty_rendaan * ($val_price_daan * (1 + $val_adjustment) * $val_kurs);
                //     return rupiah($result);
                // }

                if ($this->val == '0') {
                    $rendaanAsumsi = $rendaanValues
                        ->where('asumsi_umum_id', $a->id)
                        ->where('region_name', $query->region_name)
                        ->where('material_code', $query->material_code)
                        ->first();

                    $result = 0;
                    $cc_ = auth()->user()->company_code;

                    $query2 = DB::table('price_rendaan')
                        ->select('price_rendaan.price_rendaan_value')
                        ->where('material_code', $query->material_code)
                        ->where('region_name', $query->region_name)
                        ->where('version_id', $this->version)
                        ->where('asumsi_umum_id', $a->id)
                        ->whereNull('price_rendaan.deleted_at')
                        ->where('price_rendaan.company_code', $cc_)
                        ->first();

                    $val_qty_rendaan = $rendaanAsumsi ? $rendaanAsumsi->qty_rendaan_value : 0;
                    $val_price_daan = $query2 ? $query2->price_rendaan_value : 0;
                    $val_adjustment = $rendaanAsumsi ? $rendaanAsumsi->adjustment : 0;
                    $val_kurs = $rendaanAsumsi ? $rendaanAsumsi->usd_rate : 0;

                    if ($val_qty_rendaan > 0 && $val_price_daan == 0) {
                        return '-';
                    } else {
                        $result = $val_qty_rendaan * ($val_price_daan * (1 + ($val_adjustment / 100)));
                        if ($this->currency == 'Rupiah') {
                            return rupiah($result);
                        } elseif ($this->currency == 'Dollar') {
                            if ($result != 0) {
                                return helpDollar($result, $val_kurs);
                            } else {
                                return '$ 0';
                            }
                        }
                    }
                } else if ($this->val == '1') {
                    $rendaanAsumsi = $rendaanValues
                        ->where('asumsi_umum_id', $a->id)
                        ->where('region_name', $query->region_name)
                        ->where('material_code', $query->material_code)
                        ->first();

                    $result = 0;
                    $cc_ = auth()->user()->company_code;

                    $query2 = DB::table('price_rendaan')
                        ->select('price_rendaan.price_rendaan_value')
                        ->where('material_code', $query->material_code)
                        ->where('region_name', $query->region_name)
                        ->where('version_id', $this->version)
                        ->where('asumsi_umum_id', $a->id)
                        ->whereNull('price_rendaan.deleted_at')
                        ->where('price_rendaan.company_code', $cc_)
                        ->first();

                    $val_qty_rendaan = $rendaanAsumsi ? $rendaanAsumsi->qty_rendaan_value : 0;
                    $val_price_daan = $query2 ? $query2->price_rendaan_value : 0;
                    $val_adjustment = $rendaanAsumsi ? $rendaanAsumsi->adjustment : 0;
                    $val_kurs = $rendaanAsumsi ? $rendaanAsumsi->usd_rate : 0;

                    if ($val_qty_rendaan > 0 && $val_price_daan == 0) {
                        return '';
                    } else {
                        $result = $val_qty_rendaan * ($val_price_daan * (1 + ($val_adjustment / 100)));
                        if ($this->currency == 'Rupiah') {
                            return rupiah($result);
                        } elseif ($this->currency == 'Dollar') {
                            if ($result != 0) {
                                return helpDollar($result, $val_kurs);
                            } else {
                                return '$ 0';
                            }
                        }
                    }
                } else {
                    $rendaanAsumsi = $rendaanValues
                        ->where('asumsi_umum_id', $a->id)
                        ->where('region_name', $query->region_name)
                        ->where('material_code', $query->material_code)
                        ->first();

                    $result = 0;
                    $cc_ = auth()->user()->company_code;

                    $query2 = DB::table('price_rendaan')
                        ->select('price_rendaan.price_rendaan_value')
                        ->where('material_code', $query->material_code)
                        ->where('region_name', $query->region_name)
                        ->where('version_id', $this->version)
                        ->where('asumsi_umum_id', $a->id)
                        ->whereNull('price_rendaan.deleted_at')
                        ->where('price_rendaan.company_code', $cc_)
                        ->first();

                    $val_qty_rendaan = $rendaanAsumsi ? $rendaanAsumsi->qty_rendaan_value : 0;
                    $val_price_daan = $query2 ? $query2->price_rendaan_value : 0;
                    $val_adjustment = $rendaanAsumsi ? $rendaanAsumsi->adjustment : 0;
                    $val_kurs = $rendaanAsumsi ? $rendaanAsumsi->usd_rate : 0;

                    if ($val_qty_rendaan > 0 && $val_price_daan == 0) {
                        return '-';
                    } else {
                        return '';
                    }
                }
            });
        }
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
            ->setTableId('h_dt_total_daan')
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
