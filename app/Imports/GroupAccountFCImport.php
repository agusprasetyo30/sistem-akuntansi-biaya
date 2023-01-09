<?php

namespace App\Imports;

use App\Models\GroupAccountFC;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GroupAccountFCImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new GroupAccountFC([
            'group_account_fc' => $row['group_account_fc'],
            'group_account_fc_desc' => $row['group_account_fc_desc'],
            'company_code' => auth()->user()->company_code,
            'created_by' => auth()->user()->id,
        ]);
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
            'group_account_fc' => ['required', 'unique:group_account_fc,group_account_fc'],
            'group_account_fc_desc' => ['required'],
        ];
    }
}
