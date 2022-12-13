<?php

namespace App\Imports;

use App\Models\GroupAccount;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class GroupAccountImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new GroupAccount([
            'group_account_code' => $row['group_account_code'],
            'group_account_desc' => $row['group_account_desc'],
            'company_code' => 'B000',
            'is_active' => $row['is_active'],
            'created_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'group_account_code' => ['required', 'unique:group_account,group_account_code'],
            'group_account_desc' => ['required'],
        ];
    }
}
