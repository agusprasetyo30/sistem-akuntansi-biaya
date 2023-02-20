<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;
    protected $table = 'tarif';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'plant_code',
        'product_code',
        'group_account_fc',
        'tarif_value',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
