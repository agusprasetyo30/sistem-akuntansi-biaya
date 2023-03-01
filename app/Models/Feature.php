<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $table = 'feature';
    protected $primaryKey = 'kode_unik';
    protected $keyType = 'string';

    protected $fillable = [
        'kode_unik',
        'future',
        'future_name',
        'db',
        'created_at',
        'updated_at',
    ];
}
