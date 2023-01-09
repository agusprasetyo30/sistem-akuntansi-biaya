<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GroupAccountFC extends Model
{
    use HasFactory;
    protected $table = 'group_account_fc';
    protected $primaryKey = 'group_account_fc';

    protected $fillable = [
        'group_account_fc',
        'group_account_fc_desc',
        'company_code',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function get_account($id)
    {
        $result = DB::table(DB::raw('group_account_fc ga'))
            ->leftJoin(DB::raw('general_ledger_account gl'), 'gl.group_account_fc', '=', 'ga.group_account_fc')
            ->where('gl.group_account_fc', $id)
            ->whereNull('gl.deleted_at');

        $result = $result->first();
        return $result;
    }
}
