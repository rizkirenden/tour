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
        'nama_produk',
        'deskripsi',
        'include_tur',
        'paket_tour_id',
        'harga_dasar',
        'total_harga',
        'durasi_perjalanan',
        'durasi_mekkah',
        'durasi_madinah',
        'durasi_tour',
        'durasi_hari',
        'flyer',
        'kategori',
        'is_active',
    ];

    protected $casts = [
        'include_tur' => 'boolean',
        'is_active' => 'boolean',
        'harga_dasar' => 'integer',
        'total_harga' => 'integer',
        'durasi_perjalanan' => 'integer',
        'durasi_mekkah' => 'integer',
        'durasi_madinah' => 'integer',
        'durasi_tour' => 'integer',
        'durasi_hari' => 'integer',
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

    // Accessor untuk total harga formatted
    public function getTotalHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    // Accessor untuk durasi perjalanan formatted
    public function getDurasiPerjalananFormattedAttribute()
    {
        if ($this->durasi_perjalanan) {
            return $this->durasi_perjalanan . ' Hari';
        }
        return '-';
    }

    // Accessor untuk durasi mekkah formatted
    public function getDurasiMekkahFormattedAttribute()
    {
        if ($this->durasi_mekkah) {
            return $this->durasi_mekkah . ' Hari';
        }
        return '-';
    }

    // Accessor untuk durasi madinah formatted
    public function getDurasiMadinahFormattedAttribute()
    {
        if ($this->durasi_madinah) {
            return $this->durasi_madinah . ' Hari';
        }
        return '-';
    }

    // Accessor untuk durasi tour formatted
    public function getDurasiTourFormattedAttribute()
    {
        if ($this->durasi_tour) {
            return $this->durasi_tour . ' Hari';
        }
        return '-';
    }

    // Accessor untuk total durasi formatted
    public function getDurasiHariFormattedAttribute()
    {
        if ($this->durasi_hari) {
            return $this->durasi_hari . ' Hari';
        }
        return '-';
    }

    // Method untuk menghitung total durasi dari semua komponen
    public function calculateTotalDurasi()
    {
        $total = 0;
        $total += (int) ($this->durasi_perjalanan ?? 0);
        $total += (int) ($this->durasi_mekkah ?? 0);
        $total += (int) ($this->durasi_madinah ?? 0);
        $total += (int) ($this->durasi_tour ?? 0);
        return $total;
    }

    // Accessor untuk detail durasi lengkap
    public function getDetailDurasiAttribute()
    {
        $parts = [];
        if ($this->durasi_perjalanan) {
            $parts[] = $this->durasi_perjalanan . ' hari perjalanan';
        }
        if ($this->durasi_mekkah) {
            $parts[] = $this->durasi_mekkah . ' hari Mekkah';
        }
        if ($this->durasi_madinah) {
            $parts[] = $this->durasi_madinah . ' hari Madinah';
        }
        if ($this->durasi_tour) {
            $parts[] = $this->durasi_tour . ' hari tour';
        }
        return !empty($parts) ? implode(' + ', $parts) : '-';
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Boot method untuk auto update durasi_tour dan durasi_hari
    protected static function booted()
    {
        static::saving(function ($model) {
            // Auto update durasi_tour dari paket_tour yang dipilih
            if ($model->include_tur && $model->paket_tour_id) {
                $paketTour = PaketTour::find($model->paket_tour_id);
                if ($paketTour) {
                    $model->durasi_tour = (int) ($paketTour->durasi_hari ?? 0);
                }
            } else {
                $model->durasi_tour = 0;
            }

            // Auto calculate total durasi
            $model->durasi_hari = $model->calculateTotalDurasi();

            // Total harga hanya dari harga_dasar
            $model->total_harga = (int) ($model->harga_dasar ?? 0);
        });
    }
}
