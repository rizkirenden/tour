<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';
    protected $primaryKey = 'id_permission';

    protected $fillable = [
        'nama_permission',
        'modul',
        'aksi'
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'id_permission', 'id_role');
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'id_permission');
    }

    // Accessors
    public function getModulLabelAttribute()
    {
        $moduls = [
            'Dashboard' => 'Dashboard',
            'Master Data' => 'Master Data',
            'Jamaah' => 'Jamaah',
            'Keberangkatan' => 'Keberangkatan',
            'Transaksi' => 'Transaksi',
            'Laporan' => 'Laporan'
        ];
        return $moduls[$this->modul] ?? $this->modul;
    }

    public function getAksiLabelAttribute()
    {
        $aksis = [
            'view' => 'View',
            'create' => 'Create',
            'edit' => 'Edit',
            'delete' => 'Delete'
        ];
        return $aksis[$this->aksi] ?? $this->aksi;
    }
}
