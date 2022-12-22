<?php

namespace App\Imports;

use App\Models\QtyRenProd;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QtyRenProdImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $version;

    function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        $data = [
            'company_code' => auth()->user()->company_code,
            'material_code' => $row['material_code'],
            'version_id' => $this->version,
            'month_year' => Carbon::now(),
            'qty_renprod_desc' => $row['qty_renprod_desc'],
            'qty_renprod_value' => $row['qty_renprod_value'],
            'created_by' => auth()->user()->id,
            'created_at' => Carbon::now(),
        ];

        QtyRenProd::insert($data);
    }

    public function rules(): array
    {
        return [
            'company_code' => [''],
            'material_code' => [''],
        ];
    }
}
