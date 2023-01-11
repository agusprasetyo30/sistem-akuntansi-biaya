<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GLAccount extends Model
{
    use HasFactory;
    protected $table = 'gl_account';
    protected $primaryKey = 'gl_account';

    protected $fillable = [
        'gl_account',
        'gl_account_desc',
        'group_account_code',
        'company_code',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
