<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskonRiwayat extends Model
{
    use HasFactory;

    protected $table = 'diskon_riwayats';
    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'id_diskon',
        'nama_diskon',
        'nilai_diskon',
        'berlaku_untuk_produk',
        'kuota',
        'sudah_digunakan',
        'kuota_baru',
        'reset_ke',
        'catatan',
        'direset_oleh',
    ];

    protected $casts = [
        'nilai_diskon' => 'integer',
        'kuota' => 'integer',
        'sudah_digunakan' => 'integer',
        'kuota_baru' => 'integer',
        'reset_ke' => 'integer',
    ];

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'id_diskon', 'id_diskon');
    }

    public function getNilaiDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai_diskon, 0, ',', '.');
    }

    public function getTanggalResetFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '-';
    }
}
