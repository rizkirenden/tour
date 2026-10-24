<?php
// app/Models/Keluarga.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    use HasFactory;

    protected $table = 'keluargas';
    protected $primaryKey = 'id_keluarga';

    protected $fillable = [
        'kode_keluarga',
        'nama_keluarga',
        'produk_paket',
        'id_diskon',
        'agent',
        'fee_agent',
        'bulan_keberangkatan',
        'tahun_keberangkatan',
        'bulan_harga',
        'tahun_harga',
        'id_produk_keberangkatan',
        'id_produk_harga_bulanan',
        'total_tagihan_sebelum_diskon',
        'nilai_diskon',
        'total_diskon',
        'total_tagihan_setelah_diskon',
        'total_dibayar',
        'total_refund',
        'sisa_kelebihan_bayar',
        'sisa_tagihan',
        'status_pembayaran',
        'keterangan_diskon',
        'catatan_tambahan'
    ];

    protected $casts = [
        'bulan_keberangkatan' => 'integer',
        'tahun_keberangkatan' => 'integer',
        'bulan_harga' => 'integer',
        'tahun_harga' => 'integer',
        'fee_agent' => 'integer',
        'total_tagihan_sebelum_diskon' => 'integer',
        'nilai_diskon' => 'integer',
        'total_diskon' => 'integer',
        'total_tagihan_setelah_diskon' => 'integer',
        'total_dibayar' => 'integer',
        'total_refund' => 'integer',
        'sisa_kelebihan_bayar' => 'integer',
        'sisa_tagihan' => 'integer',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    public function jamaahs()
    {
        return $this->hasMany(Jamaah::class, 'id_keluarga', 'id_keluarga');
    }

    public function produkPaketData()
    {
        return $this->belongsTo(ProdukPaket::class, 'produk_paket', 'nama_produk');
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'id_diskon', 'id_diskon');
    }

    public function produkKeberangkatan()
    {
        return $this->belongsTo(ProdukKeberangkatan::class, 'id_produk_keberangkatan', 'id');
    }

    public function produkHargaBulanan()
    {
        return $this->belongsTo(ProdukHargaBulanan::class, 'id_produk_harga_bulanan', 'id');
    }

    public function upgradeHotels()
    {
        return $this->hasMany(JamaahUpgradeHotel::class, 'id_keluarga', 'id_keluarga')
            ->whereNull('id_jamaah');
    }

    public function transaksis()
    {
        return $this->hasManyThrough(
            TransaksiPembayaran::class,
            Jamaah::class,
            'id_keluarga',
            'id_jamaah',
            'id_keluarga',
            'id_jamaah'
        );
    }

    // ==========================================
    // ACCESSORS - FEE AGENT
    // ==========================================

    public function getFeeAgentPerJamaahAttribute()
    {
        $jumlahJamaah = $this->jamaahs->count();
        if ($jumlahJamaah <= 0) return 0;
        return (int) round($this->fee_agent / $jumlahJamaah);
    }

    public function getFeeAgentPerJamaahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->fee_agent_per_jamaah, 0, ',', '.');
    }

    public function getJumlahJamaahAttribute()
    {
        return $this->jamaahs->count();
    }

    public function getFeeAgentFormattedAttribute()
    {
        return 'Rp ' . number_format($this->fee_agent, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - STATUS & TAGIHAN
    // ==========================================

    public function getStatusPembayaranBadgeAttribute()
    {
        $colors = [
            'Belum Bayar' => 'bg-red-100 text-red-700',
            'DP' => 'bg-yellow-100 text-yellow-700',
            'Setoran' => 'bg-blue-100 text-blue-700',
            'Lunas' => 'bg-green-100 text-green-700'
        ];
        $color = $colors[$this->status_pembayaran] ?? 'bg-gray-100 text-gray-700';
        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $color . '">' . $this->status_pembayaran . '</span>';
    }

    public function getTotalTagihanSetelahDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tagihan_setelah_diskon, 0, ',', '.');
    }

    public function getTotalDibayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_dibayar, 0, ',', '.');
    }

    public function getSisaTagihanAttribute($value)
    {
        // ✅ Paksa non-negatif untuk data lama
        return max(0, (int) $value);
    }

    public function getSisaTagihanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->sisa_tagihan, 0, ',', '.');
    }

    // ==========================================
    // ✅ ACCESSORS - REFUND / KELEBIHAN BAYAR
    // ==========================================

    public function getKelebihanBayarAttribute()
    {
        $dibayar = (int) ($this->total_dibayar ?? 0);
        $tagihan = (int) ($this->total_tagihan_setelah_diskon ?? 0);
        return max(0, $dibayar - $tagihan);
    }

    public function getKelebihanBayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->kelebihan_bayar, 0, ',', '.');
    }

    public function getSisaKelebihanBayarAttribute()
    {
        if (isset($this->attributes['sisa_kelebihan_bayar'])) {
            return max(0, (int) $this->attributes['sisa_kelebihan_bayar']);
        }
        return max(0, $this->kelebihan_bayar - (int) ($this->total_refund ?? 0));
    }

    public function getSisaKelebihanBayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->sisa_kelebihan_bayar, 0, ',', '.');
    }

    public function getTotalRefundFormattedAttribute()
    {
        return 'Rp ' . number_format((int) ($this->total_refund ?? 0), 0, ',', '.');
    }

    public function getHasKelebihanBayarAttribute()
    {
        return $this->sisa_kelebihan_bayar > 0;
    }

    public function getKelebihanBayarBadgeAttribute()
    {
        if ($this->sisa_kelebihan_bayar > 0) {
            return '<span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                        <i class="fas fa-hand-holding-usd mr-1"></i> Kelebihan: ' . $this->sisa_kelebihan_bayar_formatted . '
                    </span>';
        }
        return '';
    }

    public function getNetDibayarAttribute()
    {
        return max(0, (int) ($this->total_dibayar ?? 0) - (int) ($this->total_refund ?? 0));
    }

    public function getNetDibayarFormattedAttribute()
    {
        return 'Rp ' . number_format($this->net_dibayar, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - LAINNYA
    // ==========================================

    public function getKodeKeluargaFormattedAttribute()
    {
        return $this->kode_keluarga ?? '-';
    }

    public function getTotalUpgradeHotelAttribute()
    {
        return $this->upgradeHotels->sum('total_harga');
    }

    public function getTotalUpgradeHotelFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_upgrade_hotel, 0, ',', '.');
    }

    public function getUpgradeHotelPerJamaahAttribute()
    {
        $jumlahJamaah = $this->jamaahs->count();
        if ($jumlahJamaah <= 0) return 0;
        return (int) round($this->total_upgrade_hotel / $jumlahJamaah);
    }

    public function getUpgradeHotelPerJamaahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->upgrade_hotel_per_jamaah, 0, ',', '.');
    }

    public function getTotalDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_diskon ?? 0, 0, ',', '.');
    }

    public function getDiskonPerOrangFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai_diskon ?? 0, 0, ',', '.');
    }

    public function getTotalTagihanSebelumDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tagihan_sebelum_diskon ?? 0, 0, ',', '.');
    }

    public function getBulanKeberangkatanNamaAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanNama[$this->bulan_keberangkatan] ?? '-';
    }

    public function getBulanHargaNamaAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return $bulanNama[$this->bulan_harga] ?? '-';
    }

    public function getKeberangkatanFormattedAttribute()
    {
        if ($this->bulan_keberangkatan && $this->tahun_keberangkatan) {
            return $this->bulan_keberangkatan_nama . ' ' . $this->tahun_keberangkatan;
        }
        return '-';
    }

    public function getHargaBulanFormattedAttribute()
    {
        if ($this->bulan_harga && $this->tahun_harga) {
            return $this->bulan_harga_nama . ' ' . $this->tahun_harga;
        }
        return '-';
    }

    // ==========================================
    // GENERATE KODE
    // ==========================================

    public static function generateKodeKeluarga()
    {
        $prefix = 'KLG';
        $year = date('Y');
        $month = date('m');
        $last = self::where('kode_keluarga', 'like', "{$prefix}-%")->count();
        $number = str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        return "{$prefix}-{$year}{$month}-{$number}";
    }
}
