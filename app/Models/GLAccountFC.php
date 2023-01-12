<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GLAccountFC extends Model
{
    use HasFactory;
    protected $table = 'gl_account_fc';
    protected $primaryKey = 'gl_account_fc';
    protected $keyType = 'string';

    protected $fillable = [
        'gl_account_fc',
        'gl_account_fc_desc',
        'group_account_fc',
        'company_code',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
