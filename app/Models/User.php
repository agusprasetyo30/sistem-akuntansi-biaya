<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'username',
        'password',
        'company_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function mapping_role()
    {
        return $this->hasMany(MappingRole::class, 'user_id', 'id');
    }

    public function mapping_side_bar_akses()
    {
        $data = $this->mapping_role()->with('mapping_fitur')->get()->pluck('mapping_fitur.*.id');
        $result = array_merge(...$data);
        return $result;
    }

    public function mapping_akses($feature)
    {
        $data = $this->mapping_role()->with(['mapping_fitur' => function ($query) use ($feature) {
            $query->where('db', $feature);
        }])->whereHas('mapping_fitur', function ($query) use ($feature) {
            $query->where('db', $feature);
        })->get()->pluck('mapping_fitur.*')->all();

        $result = array_merge(...$data);

        return $result[0] ?? false;
    }
}
