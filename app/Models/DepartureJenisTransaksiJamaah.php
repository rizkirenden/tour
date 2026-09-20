<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartureJenisTransaksiJamaah extends Model
{
    use HasFactory;

    protected $table = 'departure_jenis_transaksi_jamaah';

    protected $fillable = [
        'id_departure_jenis_transaksi',
        'id_jamaah',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'status_terima',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function departureJenisTransaksi()
    {
        return $this->belongsTo(DepartureJenisTransaksi::class, 'id_departure_jenis_transaksi', 'id');
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'id_jamaah', 'id_jamaah');
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga ?? 0, 0, ',', '.');
    }

    public function getHargaSatuanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan ?? 0, 0, ',', '.');
    }
}