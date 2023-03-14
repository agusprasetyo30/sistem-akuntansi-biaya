<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingRole extends Model
{
    use HasFactory;
    protected $table = 'mapping_role';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'role_id',
        'login_method',
        'created_at',
        'updated_at',
    ];
    public function mapping_fitur(){
        return $this->hasMany(Management_Role::class, 'role_id', 'role_id');
    }

}
