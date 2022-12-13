<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kurs extends Model
{
    use HasFactory;
    protected $table = 'kurs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'usd_rate',
        'month',
        'year',
        'created_at',
        'updated_at',
    ];
}
