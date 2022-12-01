<?php

namespace App\Imports;

use App\Models\Periode;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PeriodeImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Periode([
            'periode_name' => $row['periode_name'],
            'awal_periode' => $row['awal_periode'],
            'akhir_periode' => $row['akhir_periode'],
            // 'awal_periode' => date('Y-m-d', strtotime($row['awal_periode'])),
            // 'akhir_periode' => date('Y-m-d', strtotime($row['akhir_periode'])),
            'is_active' => $row['is_active'],
            'created_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array{
        return[
            'periode_name' => ['required'],
        ];
    }
}
