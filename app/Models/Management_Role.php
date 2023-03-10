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
        'user_id',
        'role_id',
        'username',
        'login_method',
        'kode_feature',
        'create',
        'read',
        'update',
        'delete',
        'approve',
        'submit',
        'created_at',
        'updated_at',
        'company_code'
    ];

}
