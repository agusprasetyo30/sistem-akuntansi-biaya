<?php

namespace App\Imports;

use App\Models\Asumsi_Umum;
use App\Models\PriceRenDaan;
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

class PriceRenDaanImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithMultipleSheets, WithBatchInserts, WithChunkReading, ToCollection
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $version;
    protected $currency;
    protected $kurs;

    public function __construct($version, $currency, $kurs)
    {
        $this->version = $version;
        $this->currency = $currency;
        $this->kurs = $kurs;
    }
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        $lengthPeriode = count($row);
        $list = [];
        $arrHeader = array_keys($row);
        $arr = array_values($row);

        for ($i = 0; $i < $lengthPeriode; $i++) {

            if ($i > 1){
                $temp_date = explode('_', $arrHeader[$i]);
                $date = $temp_date[0].'-'.$temp_date[1];

                $versi = Asumsi_Umum::where('month_year', 'ilike', '%'.$date.'%')
                    ->where('version_id', $this->version)
                    ->first();

                $input['price_rendaan_value'] = $arr[$i] != null ?(double) str_replace(',', '.', $arr[$i]) :0;
                $input['asumsi_umum_id'] = $versi->id;
                $input['type_currency'] = $this->currency;
                $input['version_id'] = $this->version;
                $input['company_code'] = auth()->user()->company_code;
                $input['created_by'] = auth()->user()->id;
                $input['updated_by'] = auth()->user()->id;
                array_push($list, $input);
            }else{
                $input[$arrHeader[$i]] = $arr[$i];
            }
        }
        collect($list)->each(function ($result, $key){
            if ($this->currency != 'IDR'){
//                dd($result['price_rendaan_value']);
                $result['price_rendaan_value'] = $result['price_rendaan_value'] * $this->kurs[$key]['usd_rate'];
            }
//            dd($result);
            PriceRenDaan::create($result);
        });
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
            'material_code' => 'required',
            'region_name' => 'required',
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
