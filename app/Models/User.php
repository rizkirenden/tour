<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $incrementing = true;

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'telepon',
        'role',
        'foto_profile',
        'last_login',
        'is_active'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role', 'nama_role');
    }

    // Accessors
    public function getRoleLabelAttribute()
    {
        $roles = [
            'Super Admin' => 'Super Admin',
            'Admin' => 'Admin',
            'Manager' => 'Manager',
            'Operasional' => 'Operasional'
        ];
        return $roles[$this->role] ?? $this->role;
    }

    // Mutators
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
