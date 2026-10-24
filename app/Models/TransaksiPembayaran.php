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
}
