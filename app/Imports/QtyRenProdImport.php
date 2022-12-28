<?php

namespace App\Imports;

use App\Models\Asumsi_Umum;
use App\Models\QtyRenProd;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class QtyRenProdImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithMultipleSheets, WithBatchInserts, WithChunkReading, ToCollection, ShouldQueue
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $version;

    function __construct($version)
    {
        $this->version = $version;
    }

    public function model(array $row)
    {
        $lengthPeriode = count($row);
        $list = [];
        $arrHeader = array_keys($row);
        $arr = array_values($row);

        for ($i = 1; $i < $lengthPeriode; $i++) {
            // $dy = substr($arrHeader[$i], 0, 4);
            $head = str_replace("_", "-", $arrHeader[$i]);
            $periode = $head . '-01 00:00:00';
            $verasum = Asumsi_Umum::where('version_id', $this->version)->where('month_year', $periode)->first();

            // $list = [$arr[0], $arr[$i], $arrHeader[$i]];
            // $dt = date('Y-m-d', strtotime($arrHeader[$i]));

            // $asum_id = substr($arrHeader[$i], 8);
            // $verasum = Asumsi_Umum::where('id', $asum_id)->first();
            // $dy = substr($arrHeader[$i], 0, 4);
            // $dm = substr($arrHeader[$i], 5, 2);
            // $year = $dy . '-' . $dm . '-01';
            // $dt = date_format($arrHeader[$i], "Y-m-01");
            $list = [
                'company_code' => auth()->user()->company_code,
                'material_code' => $arr[0],
                'version_id' => $this->version,
                'asumsi_umum_id' => $verasum->id,
                'qty_renprod_value' => (float) $arr[$i],
                'created_by' => auth()->user()->id,
                'created_at' => Carbon::now(),
            ];

            QtyRenProd::insert($list);
        }
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function rules(): array
    {
        return [
            'material_code' => ['required'],
        ];
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $collection)
    {
        return $collection;
    }
}
