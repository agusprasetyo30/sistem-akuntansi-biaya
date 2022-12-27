<?php

namespace App\Imports;

use App\Models\ConsRate;
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

class ConsRateImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithMultipleSheets, WithBatchInserts, WithChunkReading, ToCollection
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function model(array $row){
        $arrHeader = array_keys($row);
        $arrHeader[1]='month_year';
        $arrvalue = array_values($row);
        $arrvalue[1] = $arrvalue[1].'-01';
        $arrvalue[4] = $arrvalue[4] != null ? $arrvalue[4] : 0;
        $data = array_combine($arrHeader, $arrvalue);
        $data['version_id'] = $this->version;
        $data['company_code'] = 'B000';
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        ConsRate::create($data);
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
            'plant_code' => ['required'],
        ];
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        return $collection;
    }
}
