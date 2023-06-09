<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management_Role extends Model
{
    use HasFactory;
    protected $table = 'management_role';
    protected $primaryKey = 'id';

    protected $fillable = [
        'role_id',
        'kode_feature',
        'create',
        'read',
        'update',
        'delete',
        'approve',
        'submit',
        'db',
        'created_at',
        'updated_at',
        'company_code'
    ];

}
