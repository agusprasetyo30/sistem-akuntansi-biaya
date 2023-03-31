<?php

namespace App\Imports;

use App\Models\zco;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class Zco2Import implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $collection)
    {
        return $collection;
    }
}
