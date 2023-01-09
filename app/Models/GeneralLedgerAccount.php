<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedgerAccount extends Model
{
    use HasFactory;
    protected $table = 'general_ledger_account';
    protected $primaryKey = 'general_ledger_account';

    protected $fillable = [
        'general_ledger_account',
        'general_ledger_account_desc',
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
