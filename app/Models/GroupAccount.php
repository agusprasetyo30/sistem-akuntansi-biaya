<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupAccount extends Model
{
    use HasFactory;

    protected $table = 'group_account';
    protected $primaryKey = 'group_account_code';
    protected $keyType = 'string';

    protected $fillable = [
        'company_code',
        'group_account_code',
        'group_account_desc',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function get_account($id)
    {
        $result = DB::table(DB::raw('group_account ga'))
            ->leftJoin(DB::raw('material mat'), 'mat.group_account_code', '=', 'ga.group_account_code')
            ->where('mat.group_account_code', $id)
            ->whereNull('mat.deleted_at');

        $result = $result->first();
        return $result;
    }
}
