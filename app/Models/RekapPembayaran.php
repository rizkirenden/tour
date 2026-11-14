<?php
// app/Models/RekapPembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapPembayaran extends Model
{
    protected $table = 'transaksi_pembayarans';

    // Model ini hanya untuk query helper (tidak untuk CRUD).
    // Gunakan query builder langsung di Service.

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah_bayar' => 'integer',
    ];
}
