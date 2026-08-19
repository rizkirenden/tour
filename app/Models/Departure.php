<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departure extends Model
{
    use HasFactory;

    protected $table = 'departures';
    protected $primaryKey = 'id_departure';

    protected $fillable = [
        'produk_paket',
        'nama_keberangkatan',
        'tanggal_keberangkatan',
        'tanggal_kepulangan',
        'kuota',
        'jamaah_terdaftar',
        'status',
        'total_pendapatan_kotor',
        'total_diskon',
        'total_pendapatan_bersih',
        'total_hpp',
        'laba_bersih',
        'margin_laba'
    ];

    protected $casts = [
        'tanggal_keberangkatan' => 'date',
        'tanggal_kepulangan' => 'date',
        'total_pendapatan_kotor' => 'integer',
        'total_diskon' => 'integer',
        'total_pendapatan_bersih' => 'integer',
        'total_hpp' => 'integer',
        'laba_bersih' => 'integer',
        'margin_laba' => 'decimal:2'
    ];

    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'produk_paket', 'nama_produk');
    }

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'id_keberangkatan', 'nama_keberangkatan');
    }

    public function keluargas()
    {
        return $this->hasMany(Keluarga::class, 'id_departure');
    }

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'id_departure');
    }

    public function hppDetails()
    {
        return $this->hasMany(HppDetail::class, 'departure', 'nama_keberangkatan');
    }

    public function transaksiPengeluarans()
    {
        return $this->hasMany(TransaksiPengeluaran::class, 'departure', 'nama_keberangkatan');
    }

    public function getStatusLabelAttribute()
    {
        $status = StatusKeberangkatan::where('nama_status', $this->status)->first();
        return $status ? $status->nama_status : $this->status;
    }

    public function getStatusWarnaAttribute()
    {
        $status = StatusKeberangkatan::where('nama_status', $this->status)->first();
        return $status ? $status->warna : '#808080';
    }

    public function getTotalPendapatanKotorFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pendapatan_kotor, 0, ',', '.');
    }

    public function getTotalPendapatanBersihFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pendapatan_bersih, 0, ',', '.');
    }

    public function getLabaBersihFormattedAttribute()
    {
        return 'Rp ' . number_format($this->laba_bersih, 0, ',', '.');
    }

    public function getSisaKuotaAttribute()
    {
        return $this->kuota - $this->jamaah_terdaftar;
    }

    public function getIsFullAttribute()
    {
        return $this->jamaah_terdaftar >= $this->kuota;
    }
}
