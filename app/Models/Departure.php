<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Departure extends Model
{
    use HasFactory;

    protected $table = 'departures';
    protected $primaryKey = 'id_departure';

    protected $fillable = [
        'id_produk',
        'produk_paket',
        'nama_keberangkatan',
        'kode_keberangkatan',
        'tanggal_keberangkatan',
        'tanggal_kepulangan',
        'bulan_keberangkatan',
        'tahun_keberangkatan',
        'kuota',
        'jamaah_terdaftar',
        'id_status',
        'id_maskapai_domestik_berangkat',
        'harga_maskapai_domestik_berangkat',
        'id_maskapai_domestik_pulang',
        'harga_maskapai_domestik_pulang',
        'id_maskapai_internasional_berangkat',
        'harga_maskapai_internasional_berangkat',
        'id_maskapai_internasional_pulang',
        'harga_maskapai_internasional_pulang',
        'id_hotel_mekkah',
        'id_hotel_madinah',
        'id_hotel_transit',
        'total_pendapatan',
        'total_diskon',
        'total_pendapatan_bersih',
        'total_pendapatan_kotor',
        'total_pengeluaran',
        'keuntungan',
        'margin_laba',
        'harga_asli_upgrade_total',
        'total_upgrade_hotel',
        'selisih_upgrade_hotel',
        'catatan',
        'is_maskapai_complete',
        'is_hotel_complete',
        'is_jamaah_complete',
        'is_catatan_complete',
        'is_perlengkapan_complete',
    ];

    protected $casts = [
        'tanggal_keberangkatan' => 'date',
        'tanggal_kepulangan' => 'date',
        'bulan_keberangkatan' => 'integer',
        'tahun_keberangkatan' => 'integer',
        'harga_maskapai_domestik_berangkat' => 'integer',
        'harga_maskapai_domestik_pulang' => 'integer',
        'harga_maskapai_internasional_berangkat' => 'integer',
        'harga_maskapai_internasional_pulang' => 'integer',
        'total_pendapatan' => 'integer',
        'total_diskon' => 'integer',
        'total_pendapatan_bersih' => 'integer',
        'total_pendapatan_kotor' => 'integer',
        'total_pengeluaran' => 'integer',
        'keuntungan' => 'integer',
        'margin_laba' => 'decimal:2',
        'harga_asli_upgrade_total' => 'integer',
        'total_upgrade_hotel' => 'integer',
        'selisih_upgrade_hotel' => 'integer',
        'is_maskapai_complete' => 'boolean',
        'is_hotel_complete' => 'boolean',
        'is_jamaah_complete' => 'boolean',
        'is_catatan_complete' => 'boolean',
        'is_perlengkapan_complete' => 'boolean',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    public function produk()
    {
        return $this->belongsTo(ProdukPaket::class, 'id_produk', 'id_produk');
    }

    public function statusKeberangkatan()
    {
        return $this->belongsTo(StatusKeberangkatan::class, 'id_status', 'id_status');
    }

    public function maskapaiDomestikBerangkat()
    {
        return $this->belongsTo(Maskapai::class, 'id_maskapai_domestik_berangkat', 'id_maskapai');
    }

    public function maskapaiDomestikPulang()
    {
        return $this->belongsTo(Maskapai::class, 'id_maskapai_domestik_pulang', 'id_maskapai');
    }

    public function maskapaiInternasionalBerangkat()
    {
        return $this->belongsTo(Maskapai::class, 'id_maskapai_internasional_berangkat', 'id_maskapai');
    }

    public function maskapaiInternasionalPulang()
    {
        return $this->belongsTo(Maskapai::class, 'id_maskapai_internasional_pulang', 'id_maskapai');
    }

    public function hotelMekkah()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel_mekkah', 'id_hotel');
    }

    public function hotelMadinah()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel_madinah', 'id_hotel');
    }

    public function hotelTransit()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel_transit', 'id_hotel');
    }

    public function hotelDetails()
    {
        return $this->hasMany(DepartureHotelDetail::class, 'id_departure', 'id_departure');
    }

    public function hotelMekkahDetails()
    {
        return $this->hasMany(DepartureHotelDetail::class, 'id_departure', 'id_departure')
            ->whereHas('hotel', fn($q) => $q->where('kota', 'Mekkah'));
    }

    public function hotelMadinahDetails()
    {
        return $this->hasMany(DepartureHotelDetail::class, 'id_departure', 'id_departure')
            ->whereHas('hotel', fn($q) => $q->where('kota', 'Madinah'));
    }

    public function hotelTransitDetails()
    {
        return $this->hasMany(DepartureHotelDetail::class, 'id_departure', 'id_departure')
            ->whereHas('hotel', fn($q) => $q->where('kota', '!=', 'Mekkah')->where('kota', '!=', 'Madinah'));
    }

    public function jamaahs()
    {
        return $this->belongsToMany(Jamaah::class, 'departure_jamaahs', 'id_departure', 'id_jamaah')
            ->withPivot('status_keberangkatan', 'catatan')
            ->withTimestamps();
    }

    public function departureJamaahs()
    {
        return $this->hasMany(DepartureJamaah::class, 'id_departure', 'id_departure');
    }

    public function departurePerlengkapan()
    {
        return $this->hasMany(DeparturePerlengkapan::class, 'id_departure', 'id_departure');
    }

    public function perlengkapan()
    {
        return $this->belongsToMany(Perlengkapan::class, 'departure_perlengkapan', 'id_departure', 'id_perlengkapan')
            ->withPivot('jumlah_per_jamaah', 'harga_satuan', 'total_harga', 'keterangan', 'is_active')
            ->withTimestamps();
    }

    public function departureJenisTransaksis()
    {
        return $this->hasMany(DepartureJenisTransaksi::class, 'id_departure', 'id_departure');
    }

    public function jenisTransaksis()
    {
        return $this->belongsToMany(JenisTransaksi::class, 'departure_jenis_transaksis', 'id_departure', 'id_jenis_transaksi')
            ->withPivot('harga_satuan', 'total_harga', 'catatan')
            ->withTimestamps();
    }

    public function departurePaketTourHotels()
    {
        return $this->hasMany(DeparturePaketTourHotel::class, 'id_departure', 'id_departure');
    }

    public function paketTourHotels()
    {
        return $this->belongsToMany(Hotel::class, 'departure_paket_tour_hotels', 'id_departure', 'id_hotel')
            ->withPivot('id_kamar', 'id_paket_tour', 'urutan', 'harga_per_malam', 'durasi_menginap', 'jumlah_kamar', 'tipe_kamar', 'catatan')
            ->withTimestamps();
    }

    public function pembayaranTakTerduga()
    {
        return $this->hasMany(DeparturePembayaranTakTerduga::class, 'id_departure', 'id_departure');
    }

    // ==========================================
    // STATUS COMPLETE BADGE
    // ==========================================

    public function getMaskapaiStatusBadgeAttribute()
    {
        return $this->is_maskapai_complete
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700"><i class="fas fa-check mr-1"></i> Lengkap</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700"><i class="fas fa-times mr-1"></i> Belum</span>';
    }

    public function getHotelStatusBadgeAttribute()
    {
        return $this->is_hotel_complete
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700"><i class="fas fa-check mr-1"></i> Lengkap</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700"><i class="fas fa-times mr-1"></i> Belum</span>';
    }

    public function getJamaahStatusBadgeAttribute()
    {
        return $this->is_jamaah_complete
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700"><i class="fas fa-check mr-1"></i> Lengkap</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700"><i class="fas fa-times mr-1"></i> Belum</span>';
    }

    public function getCatatanStatusBadgeAttribute()
    {
        return $this->is_catatan_complete
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700"><i class="fas fa-check mr-1"></i> Lengkap</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700"><i class="fas fa-times mr-1"></i> Belum</span>';
    }

    public function getPerlengkapanStatusBadgeAttribute()
    {
        return $this->is_perlengkapan_complete
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700"><i class="fas fa-check mr-1"></i> Lengkap</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700"><i class="fas fa-times mr-1"></i> Belum</span>';
    }

    public function getProgressPercentageAttribute()
    {
        $total = 5;
        $completed = 0;
        if ($this->is_maskapai_complete) $completed++;
        if ($this->is_hotel_complete) $completed++;
        if ($this->is_jamaah_complete) $completed++;
        if ($this->is_catatan_complete) $completed++;
        if ($this->is_perlengkapan_complete) $completed++;
        return round(($completed / $total) * 100);
    }

    public function getProgressColorAttribute()
    {
        $percent = $this->progress_percentage;
        if ($percent >= 75) return 'bg-green-500';
        if ($percent >= 50) return 'bg-yellow-500';
        if ($percent >= 25) return 'bg-orange-500';
        return 'bg-red-500';
    }

    // ==========================================
    // ACCESSORS - FORMATTED
    // ==========================================

    public function getTotalPendapatanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pendapatan ?? 0, 0, ',', '.');
    }

    public function getTotalDiskonFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_diskon ?? 0, 0, ',', '.');
    }

    public function getTotalPendapatanBersihFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pendapatan_bersih ?? 0, 0, ',', '.');
    }

    public function getTotalPendapatanKotorFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pendapatan_kotor ?? 0, 0, ',', '.');
    }

    public function getTotalPengeluaranFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pengeluaran ?? 0, 0, ',', '.');
    }

    public function getMarginLabaFormattedAttribute()
    {
        return number_format($this->margin_laba ?? 0, 2) . '%';
    }

    public function getKeuntunganFormattedAttribute()
    {
        return 'Rp ' . number_format($this->keuntungan ?? 0, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - MASKAPAI
    // ==========================================

    public function getHargaMaskapaiDomestikBerangkatFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_maskapai_domestik_berangkat ?? 0, 0, ',', '.');
    }

    public function getHargaMaskapaiDomestikPulangFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_maskapai_domestik_pulang ?? 0, 0, ',', '.');
    }

    public function getHargaMaskapaiInternasionalBerangkatFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_maskapai_internasional_berangkat ?? 0, 0, ',', '.');
    }

    public function getHargaMaskapaiInternasionalPulangFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_maskapai_internasional_pulang ?? 0, 0, ',', '.');
    }

    public function getTotalHargaMaskapaiAttribute()
    {
        return ($this->harga_maskapai_domestik_berangkat ?? 0) +
            ($this->harga_maskapai_domestik_pulang ?? 0) +
            ($this->harga_maskapai_internasional_berangkat ?? 0) +
            ($this->harga_maskapai_internasional_pulang ?? 0);
    }

    public function getTotalHargaMaskapaiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_maskapai, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - HOTEL
    // ==========================================

    public function getTotalHargaHotelMekkahAttribute()
    {
        return $this->hotelMekkahDetails->sum(fn($d) => $d->harga_per_malam * $d->durasi_menginap * $d->jumlah_kamar);
    }

    public function getTotalHargaHotelMadinahAttribute()
    {
        return $this->hotelMadinahDetails->sum(fn($d) => $d->harga_per_malam * $d->durasi_menginap * $d->jumlah_kamar);
    }

    public function getTotalHargaHotelTransitAttribute()
    {
        return $this->hotelTransitDetails->sum(fn($d) => $d->harga_per_malam * $d->durasi_menginap * $d->jumlah_kamar);
    }

    public function getTotalHargaHotelAllAttribute()
    {
        return $this->total_harga_hotel_mekkah + $this->total_harga_hotel_madinah + $this->total_harga_hotel_transit;
    }

    public function getTotalHargaHotelMekkahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_hotel_mekkah, 0, ',', '.');
    }

    public function getTotalHargaHotelMadinahFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_hotel_madinah, 0, ',', '.');
    }

    public function getTotalHargaHotelTransitFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_hotel_transit, 0, ',', '.');
    }

    public function getTotalHargaHotelAllFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_hotel_all, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - PERLENGKAPAN
    // ==========================================

    public function getTotalHargaPerlengkapanAttribute()
    {
        $total = 0;
        foreach ($this->departurePerlengkapan as $perlengkapan) {
            foreach ($perlengkapan->perlengkapanJamaahs as $pj) {
                if ($pj->status_terima === 'Sudah Diterima') {
                    $total += $pj->total_harga ?? 0;
                }
            }
        }
        return $total;
    }

    public function getTotalHargaPerlengkapanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_perlengkapan, 0, ',', '.');
    }

    public function getTotalHargaPerlengkapanAllAttribute()
    {
        return $this->departurePerlengkapan->sum('total_harga');
    }

    public function getTotalHargaPerlengkapanAllFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_perlengkapan_all, 0, ',', '.');
    }

    public function getTotalPerlengkapanDiterimaAttribute()
    {
        return $this->departurePerlengkapan->sum(fn($p) => $p->perlengkapanJamaahs->where('status_terima', 'Sudah Diterima')->count());
    }

    public function getTotalPerlengkapanItemAttribute()
    {
        return $this->departurePerlengkapan->sum(fn($p) => $p->perlengkapanJamaahs->count());
    }

    // ==========================================
    // ACCESSORS - JENIS TRANSAKSI
    // ==========================================

    public function getTotalJenisTransaksiAttribute()
    {
        $total = 0;
        foreach ($this->departureJenisTransaksis as $item) {
            foreach ($item->departureJenisTransaksiJamaahs as $jtj) {
                if ($jtj->status_terima === 'Sudah Diterima') {
                    $total += $jtj->total_harga ?? 0;
                }
            }
        }
        return $total;
    }

    public function getTotalJenisTransaksiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_jenis_transaksi, 0, ',', '.');
    }

    public function getTotalJenisTransaksiAllAttribute()
    {
        return $this->departureJenisTransaksis->sum('total_harga');
    }

    public function getTotalJenisTransaksiAllFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_jenis_transaksi_all, 0, ',', '.');
    }

    public function getTotalJenisTransaksiDiterimaAttribute()
    {
        return $this->departureJenisTransaksis->sum(fn($jt) => $jt->departureJenisTransaksiJamaahs->where('status_terima', 'Sudah Diterima')->count());
    }

    public function getTotalJenisTransaksiItemAttribute()
    {
        return $this->departureJenisTransaksis->sum(fn($jt) => $jt->departureJenisTransaksiJamaahs->count());
    }

    // ==========================================
    // ACCESSORS - PAKET TOUR HOTEL
    // ==========================================

    public function getTotalHargaPaketTourHotelAttribute()
    {
        return $this->departurePaketTourHotels->sum(fn($item) => $item->total_harga);
    }

    public function getTotalHargaPaketTourHotelFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_paket_tour_hotel, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - FEE PASSPORT
    // ==========================================

    public function getTotalPembayaranPassportAttribute()
    {
        return $this->jamaahs->sum(fn($j) => $j->pembayaran_passport_untuk_pengeluaran);
    }

    public function getTotalPembayaranPassportFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pembayaran_passport, 0, ',', '.');
    }

    public function getTotalPendapatanPerusahaanPassportAttribute()
    {
        return $this->jamaahs->sum(fn($j) => $j->pendapatan_perusahaan_untuk_pendapatan);
    }

    public function getTotalPendapatanPerusahaanPassportFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pendapatan_perusahaan_passport, 0, ',', '.');
    }

    public function getTotalFeePassportAttribute()
    {
        return $this->total_pembayaran_passport + $this->total_pendapatan_perusahaan_passport;
    }

    public function getTotalFeePassportFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_fee_passport, 0, ',', '.');
    }

    public function getJamaahDenganPassportAttribute()
    {
        return $this->jamaahs->filter(fn($j) => $j->is_pembuatan_passport_efektif)->count();
    }

    public function getJamaahTanpaPassportAttribute()
    {
        return $this->jamaahs->filter(fn($j) => !$j->is_pembuatan_passport_efektif)->count();
    }

    // ==========================================
    // ACCESSORS - STATUS
    // ==========================================

    public function getStatusBadgeAttribute()
    {
        if ($this->statusKeberangkatan) {
            $warna = $this->statusKeberangkatan->warna ?? 'bg-gray-100 text-gray-700';
            return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $warna . '">' . $this->statusKeberangkatan->nama_status . '</span>';
        }
        return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Tanpa Status</span>';
    }

    public function getStatusNamaAttribute()
    {
        return $this->statusKeberangkatan ? $this->statusKeberangkatan->nama_status : '-';
    }

    public function getHotelMekkahNamaAttribute()
    {
        return $this->hotelMekkah ? $this->hotelMekkah->nama_hotel : '-';
    }

    public function getHotelMadinahNamaAttribute()
    {
        return $this->hotelMadinah ? $this->hotelMadinah->nama_hotel : '-';
    }

    public function getHotelTransitNamaAttribute()
    {
        return $this->hotelTransit ? $this->hotelTransit->nama_hotel : '-';
    }

    // ==========================================
    // ACCESSORS - UPGRADE HOTEL
    // ==========================================

    public function getTotalUpgradeHotelPersonalAttribute()
    {
        return $this->jamaahs->sum(fn($j) => $j->upgradeHotels->sum('total_harga'));
    }

    public function getTotalUpgradeHotelPersonalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_upgrade_hotel_personal, 0, ',', '.');
    }

    public function getTotalUpgradeHotelKeluargaAttribute()
    {
        $total = 0;
        $processedKeluarga = [];

        foreach ($this->jamaahs as $jamaah) {
            if (!$jamaah->id_keluarga) continue;
            if (in_array($jamaah->id_keluarga, $processedKeluarga)) continue;

            $keluarga = $jamaah->keluarga;
            if (!$keluarga) continue;

            $processedKeluarga[] = $jamaah->id_keluarga;
            $jumlahJamaahKeluarga = $keluarga->jamaahs->count();
            if ($jumlahJamaahKeluarga <= 0) continue;

            $sharePerJamaah = (int) round($keluarga->upgradeHotels->sum('total_harga') / $jumlahJamaahKeluarga);
            $jamaahKeluargaDiDeparture = $this->jamaahs
                ->where('id_keluarga', $jamaah->id_keluarga)
                ->count();

            $total += $sharePerJamaah * $jamaahKeluargaDiDeparture;
        }

        return $total;
    }

    public function getTotalUpgradeHotelKeluargaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_upgrade_hotel_keluarga, 0, ',', '.');
    }

    public function getTotalUpgradeHotelGabunganAttribute()
    {
        return $this->total_upgrade_hotel_personal + $this->total_upgrade_hotel_keluarga;
    }

    public function getTotalUpgradeHotelGabunganFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_upgrade_hotel_gabungan, 0, ',', '.');
    }

    public function getHargaAsliUpgradeTotalFormattedAttribute()
    {
        return 'Rp ' . number_format($this->harga_asli_upgrade_total ?? 0, 0, ',', '.');
    }

    public function getSelisihUpgradeHotelAttribute()
    {
        return $this->total_upgrade_hotel_gabungan - ($this->harga_asli_upgrade_total ?? 0);
    }

    public function getSelisihUpgradeHotelFormattedAttribute()
    {
        $selisih = $this->selisih_upgrade_hotel;
        $prefix = $selisih >= 0 ? '+' : '−';
        return $prefix . ' Rp ' . number_format(abs($selisih), 0, ',', '.');
    }

    public function getSelisihUpgradeHotelStatusAttribute()
    {
        $selisih = $this->selisih_upgrade_hotel;
        if ($selisih > 0) {
            return [
                'status' => 'profit',
                'label' => 'Masuk Pendapatan',
                'badge' => '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700"><i class="fas fa-arrow-up mr-1"></i> Profit</span>',
                'color' => 'text-green-600',
                'description' => 'Dibayar jamaah lebih besar dari modal → masuk pendapatan',
            ];
        } elseif ($selisih < 0) {
            return [
                'status' => 'minus',
                'label' => 'Minus (Rugi)',
                'badge' => '<span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700"><i class="fas fa-arrow-down mr-1"></i> Minus</span>',
                'color' => 'text-red-600',
                'description' => 'Dibayar jamaah lebih kecil dari modal → masuk pengeluaran (perusahaan nombok)',
            ];
        }
        return [
            'status' => 'sama',
            'label' => 'Sama',
            'badge' => '<span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">Sama</span>',
            'color' => 'text-gray-500',
            'description' => 'Dibayar jamaah sama dengan modal',
        ];
    }

    // ==========================================
    // ACCESSORS - PEMBAYARAN TAK TERDUGA
    // ==========================================

    public function getTotalPembayaranTakTerdugaAttribute()
    {
        return $this->pembayaranTakTerduga->sum('total_pembayaran');
    }

    public function getTotalPembayaranTakTerdugaFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_pembayaran_tak_terduga, 0, ',', '.');
    }

    // ==========================================
    // METHODS
    // ==========================================

    public static function generateKode($produkKode)
    {
        $year = date('Y');
        $month = date('m');
        $last = self::whereYear('created_at', $year)->count() + 1;
        $number = str_pad($last, 4, '0', STR_PAD_LEFT);
        return $produkKode . '-' . $year . $month . '-' . $number;
    }

    public function recalculate()
    {
        DB::transaction(function () {
            $this->load([
                'jamaahs',
                'jamaahs.diskon',
                'jamaahs.upgradeHotels',
                'jamaahs.keluarga.upgradeHotels',
                'jamaahs.keluarga.jamaahs',
                'departurePerlengkapan.perlengkapanJamaahs',
                'departureJenisTransaksis.departureJenisTransaksiJamaahs',
                'departureJenisTransaksis',
                'departurePaketTourHotels',
                'hotelMekkahDetails',
                'hotelMadinahDetails',
                'hotelTransitDetails',
                'pembayaranTakTerduga',
            ]);

            // 1. TOTAL PENDAPATAN KOTOR
            $totalPendapatanKotor = 0;
            foreach ($this->jamaahs as $jamaah) {
                $totalPendapatanKotor += $jamaah->total_tagihan_setelah_diskon ?? 0;
            }

            $totalPendapatanPerusahaanPassport = 0;
            foreach ($this->jamaahs as $jamaah) {
                $totalPendapatanPerusahaanPassport += $jamaah->pendapatan_perusahaan_untuk_pendapatan;
            }

            $this->total_pendapatan_kotor = $totalPendapatanKotor;

            // 2. Total Diskon
            $totalDiskon = 0;
            foreach ($this->jamaahs as $jamaah) {
                $totalDiskon += $jamaah->total_diskon ?? 0;
            }
            $this->total_diskon = $totalDiskon;

            // 3. Total Pendapatan
            $this->total_pendapatan = $totalPendapatanKotor + $totalDiskon + $totalPendapatanPerusahaanPassport;

            // 4. Total Pendapatan Bersih
            $this->total_pendapatan_bersih = $totalPendapatanKotor + $totalPendapatanPerusahaanPassport;

            // 5. TOTAL PENGELUARAN
            $totalPengeluaran = 0;

            // Maskapai
            $totalPengeluaran += $this->harga_maskapai_domestik_berangkat ?? 0;
            $totalPengeluaran += $this->harga_maskapai_domestik_pulang ?? 0;
            $totalPengeluaran += $this->harga_maskapai_internasional_berangkat ?? 0;
            $totalPengeluaran += $this->harga_maskapai_internasional_pulang ?? 0;

            // Hotel
            foreach ($this->hotelMekkahDetails as $detail) $totalPengeluaran += $detail->total_harga;
            foreach ($this->hotelMadinahDetails as $detail) $totalPengeluaran += $detail->total_harga;
            foreach ($this->hotelTransitDetails as $detail) $totalPengeluaran += $detail->total_harga;

            // Hotel Tour
            foreach ($this->departurePaketTourHotels as $item) $totalPengeluaran += $item->total_harga;

            // Perlengkapan (diterima)
            foreach ($this->departurePerlengkapan as $perlengkapan) {
                foreach ($perlengkapan->perlengkapanJamaahs as $pj) {
                    if ($pj->status_terima === 'Sudah Diterima') {
                        $totalPengeluaran += $pj->total_harga ?? 0;
                    }
                }
            }

            // Jenis Transaksi (diterima)
            foreach ($this->departureJenisTransaksis as $item) {
                foreach ($item->departureJenisTransaksiJamaahs as $jtj) {
                    if ($jtj->status_terima === 'Sudah Diterima') {
                        $totalPengeluaran += $jtj->total_harga ?? 0;
                    }
                }
            }

            // Fee Agent
            foreach ($this->jamaahs as $jamaah) {
                $totalPengeluaran += $jamaah->fee_agent ?? 0;
            }

            // Pembayaran Passport
            foreach ($this->jamaahs as $jamaah) {
                $totalPengeluaran += $jamaah->pembayaran_passport_untuk_pengeluaran;
            }

            // PEMBAYARAN TAK TERDUGA (masuk pengeluaran)
            foreach ($this->pembayaranTakTerduga as $ptt) {
                $totalPengeluaran += $ptt->total_pembayaran ?? 0;
            }

            $this->total_pengeluaran = $totalPengeluaran;

            // UPGRADE HOTEL — Selisih = Dibayar jamaah − Modal
            $totalUpgradePersonal = $this->jamaahs->sum(fn($j) => $j->upgradeHotels->sum('total_harga'));
            $totalUpgradeKeluarga = 0;
            $processedKeluarga = [];

            foreach ($this->jamaahs as $jamaah) {
                if (!$jamaah->id_keluarga) continue;
                if (in_array($jamaah->id_keluarga, $processedKeluarga)) continue;

                $keluarga = $jamaah->keluarga;
                if (!$keluarga) continue;

                $processedKeluarga[] = $jamaah->id_keluarga;
                $jumlahJamaahKeluarga = $keluarga->jamaahs->count();
                if ($jumlahJamaahKeluarga <= 0) continue;

                $sharePerJamaah = (int) round($keluarga->upgradeHotels->sum('total_harga') / $jumlahJamaahKeluarga);
                $jamaahKeluargaDiDeparture = $this->jamaahs
                    ->where('id_keluarga', $jamaah->id_keluarga)
                    ->count();

                $totalUpgradeKeluarga += $sharePerJamaah * $jamaahKeluargaDiDeparture;
            }

            $totalUpgrade = $totalUpgradePersonal + $totalUpgradeKeluarga;
            $this->total_upgrade_hotel = $totalUpgrade;

            $selisih = $totalUpgrade - ($this->harga_asli_upgrade_total ?? 0);
            $this->selisih_upgrade_hotel = $selisih;

            if ($selisih > 0) {
                $this->total_pendapatan_kotor += $selisih;
                $this->total_pendapatan_bersih += $selisih;
            }
            if ($selisih < 0) {
                $this->total_pengeluaran += abs($selisih);
            }

            // 6. KEUNTUNGAN
            $this->keuntungan = $this->total_pendapatan_bersih - $this->total_pengeluaran;

            // 7. MARGIN LABA
            $this->margin_laba = $this->total_pendapatan_bersih > 0
                ? round(($this->keuntungan / $this->total_pendapatan_bersih) * 100, 2)
                : 0;

            // 8. STATUS COMPLETION
            $this->is_jamaah_complete = $this->jamaahs()->count() > 0;
            $this->is_perlengkapan_complete = $this->departurePerlengkapan()->count() > 0;

            $this->save();
        });

        return $this;
    }

    public function scopeActive($query)
    {
        return $query->whereHas('statusKeberangkatan', fn($q) => $q->whereIn('nama_status', ['Aktif', 'Berangkat']));
    }

    // ==========================================
    // JAMAAH METHODS
    // ==========================================

    public function addJamaah($jamaahId, $catatan = null)
    {
        $exists = DepartureJamaah::where('id_departure', $this->id_departure)
            ->where('id_jamaah', $jamaahId)
            ->exists();

        if ($exists) throw new \Exception('Jamaah sudah terdaftar di departure ini.');

        if ($this->kuota > 0 && $this->jamaah_terdaftar >= $this->kuota) {
            throw new \Exception('Kuota sudah penuh! (Kuota: ' . $this->kuota . ', Terdaftar: ' . $this->jamaah_terdaftar . ')');
        }

        $this->jamaahs()->attach($jamaahId, [
            'status_keberangkatan' => 'Terdaftar',
            'catatan' => $catatan,
        ]);

        $this->jamaah_terdaftar = $this->jamaahs()->count();
        $this->is_jamaah_complete = $this->jamaah_terdaftar > 0;
        $this->save();

        $this->recalculate();
        return $this;
    }

    public function removeJamaah($jamaahId)
    {
        $deleted = DepartureJamaah::where('id_departure', $this->id_departure)
            ->where('id_jamaah', $jamaahId)
            ->delete();

        if (!$deleted) return $this;

        $this->jamaah_terdaftar = $this->jamaahs()->count();
        $this->is_jamaah_complete = $this->jamaah_terdaftar > 0;
        $this->save();

        $this->recalculate();
        return $this;
    }

    public function updateJamaahStatus($jamaahId, $status)
    {
        $this->jamaahs()->updateExistingPivot($jamaahId, ['status_keberangkatan' => $status]);
        return $this;
    }
}