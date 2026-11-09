<?php
// app/Models/TransaksiPembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TransaksiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pembayarans';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_jamaah',
        'id_metode_pembayaran',
        'id_jenis_transaksi',
        'id_jenis_pembayaran',       // ← BARU
        'tanggal_transaksi',
        'jumlah_bayar',
        'jenis_mutasi',
        'bukti_pembayaran',
        'keterangan',
        'keterangan_refund',
        'created_by',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah_bayar'      => 'integer',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'id_jamaah', 'id_jamaah');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode_pembayaran', 'id_metode');
    }

    public function jenisTransaksi()
    {
        return $this->belongsTo(JenisTransaksi::class, 'id_jenis_transaksi', 'id_jenis');
    }

    /** ✅ Relasi baru ke JenisPembayaran */
    public function jenisPembayaran()
    {
        return $this->belongsTo(JenisPembayaran::class, 'id_jenis_pembayaran', 'id_jenis_pembayaran');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopeMasuk($query)
    {
        return $query->where('jenis_mutasi', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('jenis_mutasi', 'keluar');
    }

    public function scopeRefund($query)
    {
        return $query->where('jenis_mutasi', 'keluar');
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getIsRefundAttribute()
    {
        return ($this->jenis_mutasi ?? 'masuk') === 'keluar';
    }

    public function getJumlahBayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    public function getTanggalTransaksiFormattedAttribute()
    {
        return $this->tanggal_transaksi
            ? Carbon::parse($this->tanggal_transaksi)->format('d/m/Y')
            : '-';
    }

    public function getBuktiPembayaranUrlAttribute()
    {
        return $this->bukti_pembayaran
            ? asset('storage/' . $this->bukti_pembayaran)
            : null;
    }

    /** ✅ Nama jenis (prioritas jenisPembayaran) */
    public function getJenisNamaAttribute()
    {
        return $this->jenisPembayaran->nama
            ?? $this->jenisTransaksi->nama
            ?? '-';
    }

    /** ✅ Kode jenis untuk badge */
    public function getJenisKodeAttribute()
    {
        $kode = strtoupper(trim((string) ($this->jenisPembayaran->kode ?? '')));

        if ($kode !== '') {
            return $kode;
        }

        $nama = strtolower(trim((string) $this->jenis_nama));

        if (str_contains($nama, 'refund') || str_contains($nama, 'pengembalian')) return 'REFUND';
        if (str_contains($nama, 'down payment') || preg_match('/\bdp\b/', $nama) || str_contains($nama, 'pembayaran awal')) return 'DP';
        if (str_contains($nama, 'angsuran') || str_contains($nama, 'cicilan')) return 'ANGSURAN';
        if (str_contains($nama, 'lunas') || str_contains($nama, 'pelunasan')) return 'LUNAS';

        return null;
    }
}
