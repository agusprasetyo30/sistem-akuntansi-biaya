<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $table = 'role';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_role',
        'is_active',
        'company_code',
        'created_at',
        'updated_at'
    ];
}
