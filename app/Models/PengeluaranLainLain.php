<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranLainLain extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_lain_lains';
    protected $primaryKey = 'id_pengeluaran_lain';

    protected $fillable = [
        'tanggal',
        'nama_pengeluaran',
        'kategori',
        'nominal',
        'keterangan',
        'id_user',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'integer',
    ];

    // Relasi user() dinonaktifkan sementara karena tabel users belum ada.
    // Aktifkan kembali saat tabel users sudah dibuat.
    //
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'id_user', 'id'); // sesuaikan PK users
    // }

    public function getNominalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal ?? 0, 0, ',', '.');
    }
}