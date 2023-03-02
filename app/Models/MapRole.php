<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapRole extends Model
{
    use HasFactory;
    protected $table = 'model_has_roles';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'model_type',
        'model_id',
    ];

    // use HasFactory;
    // protected $table = 'management_role';
    // protected $primaryKey = 'id';

    // protected $fillable = [
    //     'user_id',
    //     'role_id',
    //     'username',
    //     'login_method',
    // ];
}
