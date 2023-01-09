<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
