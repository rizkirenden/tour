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
        'id_perlengkapan',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'status_terima',
        'keterangan'
    ];

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class, 'id_jamaah');
    }

    public function perlengkapan()
    {
        return $this->belongsTo(Perlengkapan::class, 'id_perlengkapan');
    }

    public function getStatusTerimaLabelAttribute()
    {
        return $this->status_terima === 'Sudah Diterima' ? 'Sudah Diterima' : 'Belum Diterima';
    }

    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}
