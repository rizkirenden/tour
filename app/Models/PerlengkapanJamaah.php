<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerlengkapanJamaah extends Model
{
    use HasFactory;

    protected $table = 'perlengkapan_jamaahs';

    protected $fillable = [
        'id_jamaah',
        'id_departure_perlengkapan',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'status_terima',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'integer',
        'total_harga' => 'integer',
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'id_jamaah', 'id_jamaah');
    }

    public function departurePerlengkapan()
    {
        return $this->belongsTo(DeparturePerlengkapan::class, 'id_departure_perlengkapan', 'id');
    }

    public function getStatusTerimaLabelAttribute()
    {
        return $this->status_terima === 'Sudah Diterima' ? 'Sudah Diterima' : 'Belum Diterima';
    }

    public function getStatusTerimaBadgeAttribute()
    {
        if ($this->status_terima == 'Sudah Diterima') {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Sudah Diterima</span>';
        }
        return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Belum Diterima</span>';
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getHargaSatuanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }
}