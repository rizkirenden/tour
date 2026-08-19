<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPemasukan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pemasukans';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'jamaah',
        'metode_pembayaran',
        'jenis_transaksi',
        'tanggal_transaksi',
        'jumlah',
        'keterangan',
        'bukti_transfer',
        'status_validasi'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah' => 'integer'
    ];

    public function jamaahRelation()
    {
        return $this->belongsTo(Jamaah::class, 'jamaah', 'nama_lengkap');
    }

    public function metodePembayaranRelation()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran', 'nama_bank');
    }

    public function jenisTransaksiRelation()
    {
        return $this->belongsTo(JenisTransaksi::class, 'jenis_transaksi', 'nama');
    }

    public function getJumlahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getStatusValidasiLabelAttribute()
    {
        $statuses = [
            'Pending' => 'Menunggu',
            'Valid' => 'Valid',
            'Invalid' => 'Invalid'
        ];
        return $statuses[$this->status_validasi] ?? $this->status_validasi;
    }

    public function getStatusValidasiBadgeAttribute()
    {
        $badges = [
            'Pending' => 'warning',
            'Valid' => 'success',
            'Invalid' => 'danger'
        ];
        return $badges[$this->status_validasi] ?? 'secondary';
    }

    public function scopeValid($query)
    {
        return $query->where('status_validasi', 'Valid');
    }

    public function scopePending($query)
    {
        return $query->where('status_validasi', 'Pending');
    }

    public function scopeByJamaah($query, $jamaah)
    {
        return $query->where('jamaah', $jamaah);
    }
}
