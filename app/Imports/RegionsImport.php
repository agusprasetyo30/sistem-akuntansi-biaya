<?php

namespace App\Imports;

use App\Models\Regions;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RegionsImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new Regions([
        //     'region_name' => $row['region_name'],
        //     'region_desc' => $row['region_desc'],
        //     'latitude' => $row['latitude'],
        //     'longtitude' => $row['longtitude'],
        //     'is_active' => $row['is_active'],
        //     'created_by' => auth()->user()->id,
        // ]);

        $data = [
            'region_name' => $row['region_name'],
            'region_desc' => $row['region_desc'],
            'latitude' => $row['latitude'],
            'longtitude' => $row['longtitude'],
            'is_active' => $row['is_active'],
            'created_by' => auth()->user()->id,
        ];
        Regions::insert($data);
    }

    public function rules(): array{
        return[
            'region_name' => ['required'],
            'region_desc' => ['required'],
            'latitude' => ['required'],
            'longtitude' => ['required'],
        ];
    }
}