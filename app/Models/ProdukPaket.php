<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPaket extends Model
{
    use HasFactory;

    protected $table = 'produk_pakets';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'deskripsi',
        'include_tur',
        'paket_tour_id',
        'harga_dasar',
        'durasi_perjalanan',
        'durasi_mekkah',
        'durasi_madinah',
        'durasi_hari',
        'flyer',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'include_tur' => 'boolean',
        'is_active' => 'boolean',
        'harga_dasar' => 'integer', // Cast ke integer
    ];

    // Relasi ke Paket Tour
    public function paketTour()
    {
        return $this->belongsTo(PaketTour::class, 'paket_tour_id', 'id_paket_tour');
    }

    // Accessor untuk URL flyer
    public function getFlyerUrlAttribute()
    {
        if ($this->flyer) {
            return asset('storage/' . $this->flyer);
        }
        return null;
    }

    // Accessor untuk harga dasar formatted
    public function getHargaDasarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_dasar, 0, ',', '.');
    }

    // Method untuk menghitung total durasi
    public function calculateTotalDurasi()
    {
        $total = 0;

        if ($this->durasi_perjalanan) {
            $total += $this->durasi_perjalanan;
        }
        $total += $this->durasi_mekkah ?? 0;
        $total += $this->durasi_madinah ?? 0;

        return (int) $total;
    }

    // Accessor untuk durasi perjalanan
    public function getDurasiPerjalananFormattedAttribute()
    {
        if ($this->durasi_perjalanan) {
            return $this->durasi_perjalanan . ' Hari';
        }
        return '-';
    }

    // Accessor status
    public function getStatusLabelAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Tidak Aktif';
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Aktif</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Tidak Aktif</span>';
    }

    public function getIncludeTurLabelAttribute()
    {
        return $this->include_tur ? 'Ya' : 'Tidak';
    }

    public function getIncludeTurBadgeAttribute()
    {
        return $this->include_tur
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Include Tur</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">Tanpa Tur</span>';
    }
}
