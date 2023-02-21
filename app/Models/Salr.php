<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function getData($cost_center, $code)
    {
        $result = DB::table("salrs")
            ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
            ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
            ->where('salrs.cost_center', $cost_center)
            ->where('group_account_fc.group_account_fc', $code);

        $result = $result->first();
        return $result;
    }
}
