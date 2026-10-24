<?php
// app/Models/JenisTransaksi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisTransaksi extends Model
{
    use HasFactory;

    protected $table = 'jenis_transaksis';
    protected $primaryKey = 'id_jenis';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    public function transaksiPembayarans()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function departureJenisTransaksis()
    {
        return $this->hasMany(DepartureJenisTransaksi::class, 'id_jenis_transaksi', 'id_jenis');
    }

    // ==========================================
    // ACCESSORS - KODE (DITURUNKAN DARI NAMA)
    // ==========================================

    /**
     * Kode jenis transaksi diturunkan dari kolom `nama`.
     * Karena di DB tidak ada kolom `kode`, kita identifikasi berdasarkan nama.
     *
     * Hasil yang mungkin: 'DP', 'ANGSURAN', 'LUNAS', 'REFUND', atau null.
     */
    public function getKodeAttribute()
    {
        $nama = strtolower(trim((string) ($this->attributes['nama'] ?? '')));

        if ($nama === '') {
            return null;
        }

        // REFUND / pengembalian dana
        if (
            str_contains($nama, 'refund') ||
            str_contains($nama, 'pengembalian')
        ) {
            return 'REFUND';
        }

        // DP / down payment / pembayaran awal
        if (
            str_contains($nama, 'down payment') ||
            preg_match('/\bdp\b/', $nama) ||
            str_contains($nama, 'pembayaran awal')
        ) {
            return 'DP';
        }

        // ANGSURAN / cicilan
        if (
            str_contains($nama, 'angsuran') ||
            str_contains($nama, 'cicilan')
        ) {
            return 'ANGSURAN';
        }

        // LUNAS / pelunasan
        if (
            str_contains($nama, 'lunas') ||
            str_contains($nama, 'pelunasan')
        ) {
            return 'LUNAS';
        }

        return null;
    }

    /**
     * Apakah jenis transaksi ini adalah refund?
     */
    public function getIsRefundAttribute()
    {
        return $this->kode === 'REFUND';
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope untuk mencari jenis transaksi berdasarkan kode logis
     * (hasil turunan dari nama). Karena tidak ada kolom `kode`,
     * kita pakai LIKE pada kolom `nama`.
     */
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
            default => $query->whereRaw('1 = 0'), // kosong
        };
    }
}
