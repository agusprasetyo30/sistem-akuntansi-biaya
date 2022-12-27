<?php

namespace App\Imports;

use App\Models\Asumsi_Umum;
use App\Models\QtyRenDaan;
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

class KuantitiRenDaanImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithMultipleSheets, WithBatchInserts, WithChunkReading, ToCollection
{
    use Importable, SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        $lengthPeriode = count($row);
        $list = [];
        $versi = null;
        $arrHeader = array_keys($row);
        $arr = array_values($row);

        for ($i = 0; $i < $lengthPeriode; $i++) {

            if ($i > 1){
                $temp_date = explode('_', $arrHeader[$i]);

                $input['qty_rendaan_value'] = $arr[$i] != null ? $arr[$i]:0;
                $input['asumsi_umum_id'] = $temp_date[2];
                if ($versi == null){
                    $versi = $temp_date[2];
                    $data_version = Asumsi_Umum::where('id', $versi)
                        ->first();

                }
                $input['version_id'] = $data_version->version_id;
                $input['company_code'] = auth()->user()->company_code;
                $input['created_by'] = auth()->user()->id;
                $input['updated_by'] = auth()->user()->id;
                array_push($list, $input);
            }else{
                $input[$arrHeader[$i]] = $arr[$i];
            }
        }
        dd($list);
        collect($list)->each(function ($result){QtyRenDaan::create($result);});
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
            'region_id' => ['required'],
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
//    /**
//    * @param Collection $collection
//    */

}
