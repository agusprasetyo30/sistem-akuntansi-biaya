<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salr extends Model
{
    use HasFactory;
    protected $table = 'salrs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'cost_center',
        'gl_account_fc',
        'periode',
        'company_code',
        'name',
        'value',
        'partner_cost_center',
        'username',
        'material_code',
        'document_number',
        'document_number_text',
        'purchase_order',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
