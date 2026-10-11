<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProdukHargaBulanan extends Model
{
    use HasFactory;

    protected $table = 'produk_harga_bulanan';

    protected $fillable = [
        'produk_paket_id',
        'bulan',
        'tahun',
        'harga',
        'kuota',
        'flyer',
        'is_active',
    ];

    protected $casts = [
        'bulan'     => 'integer',
        'tahun'     => 'integer',
        'harga'     => 'integer',
        'kuota'     => 'integer',
        'is_active' => 'boolean',
    ];

    // ============================================
    // RELASI
    // ============================================
    public function produkPaket()
    {
        return $this->belongsTo(ProdukPaket::class, 'produk_paket_id', 'id_produk');
    }

    // ============================================
    // ACCESSOR — BULAN
    // ============================================
    public function getBulanFormattedAttribute()
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $namaBulan[$this->bulan] ?? $this->bulan;
    }

    // ============================================
    // ACCESSOR — HARGA
    // ============================================
    public function getHargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // ============================================
    // ACCESSOR — KUOTA
    // ============================================
    public function getKuotaFormattedAttribute()
    {
        return $this->kuota > 0
            ? number_format($this->kuota, 0, ',', '.') . ' Orang'
            : 'Tidak Terbatas';
    }

    /**
     * Hitung berapa jamaah yang sudah menggunakan harga bulanan ini.
     */
    public function getTerpakaiAttribute()
    {
        return DB::table('jamaah_keberangkatans')
            ->where('id_produk_harga_bulanan', $this->id)
            ->count();
    }

    /**
     * Sisa kuota. Return -1 jika tidak terbatas.
     */
    public function getSisaKuotaAttribute()
    {
        if ($this->kuota <= 0) {
            return -1;
        }
        return max(0, $this->kuota - $this->terpakai);
    }

    /**
     * Apakah kuota sudah penuh?
     */
    public function getIsFullAttribute()
    {
        if ($this->kuota <= 0) {
            return false;
        }
        return $this->terpakai >= $this->kuota;
    }

    /**
     * Apakah masih tersedia?
     */
    public function getIsAvailableAttribute()
    {
        return !$this->is_full;
    }

    /**
     * Info kuota (terpakai / total)
     */
    public function getKuotaInfoAttribute()
    {
        if ($this->kuota <= 0) {
            return 'Tidak Terbatas (terisi: ' . $this->terpakai . ')';
        }
        return $this->terpakai . ' / ' . $this->kuota . ' Orang';
    }

    /**
     * Badge kuota dengan warna berdasarkan sisa
     */
    public function getKuotaBadgeAttribute()
    {
        if ($this->kuota <= 0) {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500">Tidak Terbatas</span>';
        }

        $sisa = $this->sisa_kuota;

        if ($sisa <= 0) {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Penuh (' . $this->kuota . ')</span>';
        } elseif ($sisa <= 3) {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-700">Sisa ' . $sisa . '/' . $this->kuota . '</span>';
        } else {
            return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Sisa ' . $sisa . '/' . $this->kuota . '</span>';
        }
    }

    // ============================================
    // ACCESSOR — FLYER
    // ============================================
    public function getFlyerUrlAttribute()
    {
        if ($this->flyer) {
            return asset('storage/' . $this->flyer);
        }
        return null;
    }

    // ============================================
    // ACCESSOR — STATUS
    // ============================================
    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>'
            : '<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>';
    }

    // ============================================
    // SCOPE
    // ============================================
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->where('kuota', '<=', 0)
              ->orWhereRaw('kuota > (SELECT COUNT(*) FROM jamaah_keberangkatans WHERE id_produk_harga_bulanan = produk_harga_bulanan.id)');
        });
    }
}
