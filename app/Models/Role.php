<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_role';

    protected $fillable = [
        'nama_role',
        'deskripsi',
        'level'
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class, 'role', 'nama_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'id_role', 'id_permission');
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'id_role');
    }

    // Accessors
    public function getLevelLabelAttribute()
    {
        $levels = [
            1 => 'Tertinggi',
            2 => 'Tinggi',
            3 => 'Sedang',
            4 => 'Rendah'
        ];
        return $levels[$this->level] ?? $this->level;
    }
}
