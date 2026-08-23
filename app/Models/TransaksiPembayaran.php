<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'bukti_pembayaran',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah_bayar' => 'integer',
    ];

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

    public function getJumlahBayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    public function getTanggalTransaksiFormattedAttribute()
    {
        return $this->tanggal_transaksi ? $this->tanggal_transaksi->format('d/m/Y') : '-';
    }

    public function getBuktiPembayaranUrlAttribute()
    {
        return $this->bukti_pembayaran ? asset('storage/' . $this->bukti_pembayaran) : null;
    }
}
