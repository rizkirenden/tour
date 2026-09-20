<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartureJenisTransaksi extends Model
{
    use HasFactory;

    protected $table = 'departure_jenis_transaksis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_departure',
        'id_jenis_transaksi',
        'harga_satuan',
        'total_harga',
        'catatan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function departure()
    {
        return $this->belongsTo(Departure::class, 'id_departure', 'id_departure');
    }

    public function jenisTransaksi()
    {
        return $this->belongsTo(JenisTransaksi::class, 'id_jenis_transaksi', 'id_jenis');
    }

    public function getHargaSatuanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

/**
 * ✅ Relasi ke detail per jamaah
 */
public function departureJenisTransaksiJamaahs()
{
    return $this->hasMany(DepartureJenisTransaksiJamaah::class, 'id_departure_jenis_transaksi', 'id');
}

// ==========================================
// ACCESSORS — HANYA YANG SUDAH DITERIMA
// ==========================================

public function getTotalHargaDiterimaAttribute()
{
    return $this->departureJenisTransaksiJamaahs()
        ->where('status_terima', 'Sudah Diterima')
        ->sum('total_harga');
}

public function getTotalHargaDiterimaFormattedAttribute()
{
    return 'Rp ' . number_format($this->total_harga_diterima, 0, ',', '.');
}

}
