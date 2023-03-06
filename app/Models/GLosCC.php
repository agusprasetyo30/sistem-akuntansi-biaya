<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GLosCC extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;

    protected $table = 'glos_cc';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'plant_code',
        'cost_center',
        'material_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function renprod()
    {
        return $this->hasMany(QtyRenProd::class, 'cost_center', 'cost_center');
    }

    public function cons_rate()
    {
        return $this->belongsTo(ConsRate::class, ['product_code', 'plant_code'], ['material_code', 'plant_code']);
    }
}
