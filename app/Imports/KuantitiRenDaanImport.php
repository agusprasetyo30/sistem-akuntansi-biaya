<?php

namespace App\Imports;

use Carbon\Carbon;
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

class KuantitiRenDaanImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithMultipleSheets, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        $lengthPeriode = count($row);
        $list = [];
        $arrHeader = array_keys($row);
        $arr = array_values($row);

        dd($arr, $row);
        for ($i = 1; $i < $lengthPeriode; $i++) {
            if ($i==2){
                dd('dwadaw', $arrHeader[$i], $arrHeader);
            }

            // $list = [$arr[0], $arr[$i], $arrHeader[$i]];
            // $dt = date('Y-m-d', strtotime($arrHeader[$i]));
//            $dy = substr($arrHeader[$i], 0, 4);
//            $dm = substr($arrHeader[$i], 5, 2);
//            $year = $dy . '-' . $dm . '-01';
//            // $dt = date_format($arrHeader[$i], "Y-m-01");
//            $list = [
//                'company_code' => auth()->user()->company_code,
//                'material_code' => $arr[0],
//                'version_id' => $this->version,
//                'month_year' => $year,
//                'qty_renprod_value' => (float) $arr[$i],
//                'created_by' => auth()->user()->id,
//                'created_at' => Carbon::now(),
//            ];
        }
        dd($row, $lengthPeriode, $arrHeader, $arr, $list);
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
//    /**
//    * @param Collection $collection
//    */
//    public function collection(Collection $collection)
//    {
//        return $collection;
//    }
}
