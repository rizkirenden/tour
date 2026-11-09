<?php
// app/Models/JenisPembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    use HasFactory;

    protected $table = 'jenis_pembayarans';
    protected $primaryKey = 'id_jenis_pembayaran';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    // ==========================================
    // ACCESSORS - KODE (DITURUNKAN DARI NAMA)
    // ==========================================

    public function getKodeAttribute()
    {
        $nama = strtolower(trim((string) ($this->attributes['nama'] ?? '')));

        if ($nama === '') {
            return null;
        }

        if (str_contains($nama, 'refund') || str_contains($nama, 'pengembalian')) {
            return 'REFUND';
        }

        if (
            str_contains($nama, 'down payment') ||
            preg_match('/\bdp\b/', $nama) ||
            str_contains($nama, 'pembayaran awal')
        ) {
            return 'DP';
        }

        if (str_contains($nama, 'angsuran') || str_contains($nama, 'cicilan')) {
            return 'ANGSURAN';
        }

        if (str_contains($nama, 'lunas') || str_contains($nama, 'pelunasan')) {
            return 'LUNAS';
        }

        return null;
    }

    public function getIsRefundAttribute()
    {
        return $this->kode === 'REFUND';
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeByKode($query, $kode)
    {
        $kode = strtoupper(trim((string) $kode));

        return match ($kode) {
            'REFUND' => $query->where(function ($q) {
                $q->where('nama', 'like', '%refund%')
                    ->orWhere('nama', 'like', '%pengembalian%');
            }),
            'DP' => $query->where(function ($q) {
                $q->where('nama', 'like', '%down payment%')
                    ->orWhereRaw('LOWER(nama) REGEXP ?', ['(^|[^a-z])dp([^a-z]|$)'])
                    ->orWhere('nama', 'like', '%pembayaran awal%');
            }),
            'ANGSURAN' => $query->where(function ($q) {
                $q->where('nama', 'like', '%angsuran%')
                    ->orWhere('nama', 'like', '%cicilan%');
            }),
            'LUNAS' => $query->where(function ($q) {
                $q->where('nama', 'like', '%lunas%')
                    ->orWhere('nama', 'like', '%pelunasan%');
            }),
            default => $query->whereRaw('1 = 0'),
        };
    }
}
