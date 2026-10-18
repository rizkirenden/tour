<?php

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

    // ==========================================
    // ACCESSORS
    // ==========================================

    public function getFeeAgentFormattedAttribute()
    {
        return 'Rp ' . number_format($this->fee_agent, 0, ',', '.');
    }

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

    public function getSisaTagihanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->sisa_tagihan, 0, ',', '.');
    }

    public function getKodeKeluargaFormattedAttribute()
    {
        return $this->kode_keluarga ?? '-';
    }

    // ==========================================
    // DISKON & TAGIHAN
    // ==========================================

    /** Total diskon kelompok (misal: Rp 2.000.000) */
    public function getTotalDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_diskon ?? 0, 0, ',', '.');
    }

    /** Diskon per orang (hasil pembagian) */
    public function getDiskonPerOrangFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nilai_diskon ?? 0, 0, ',', '.');
    }

    /** Harga kotor sebelum diskon (total kelompok) */
    public function getTotalTagihanSebelumDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_tagihan_sebelum_diskon ?? 0, 0, ',', '.');
    }

    // ==========================================
    // KEBERANGKATAN & HARGA
    // ==========================================

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
