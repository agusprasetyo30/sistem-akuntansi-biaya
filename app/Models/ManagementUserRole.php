<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagementUserRole extends Model
{
    use HasFactory;
    protected $table = 'mapping_role';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'role_id',
        'username',
        'login_method',
    ];
}
