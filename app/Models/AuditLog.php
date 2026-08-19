<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'module',
        'action',
        'record_id',
        'old_data',
        'new_data',
        'ip_address',
        'created_at'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function getActionLabelAttribute()
    {
        return [
            'create' => 'Tambah',
            'update' => 'Ubah',
            'delete' => 'Hapus'
        ][$this->action] ?? $this->action;
    }

    public function getActionBadgeAttribute()
    {
        return [
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger'
        ][$this->action] ?? 'secondary';
    }
}