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
            ->whereHas('hotel', function ($q) {
                $q->where('kota', 'Mekkah');
            });
    }

    public function hotelMadinahDetails()
    {
        return $this->hasMany(DepartureHotelDetail::class, 'id_departure', 'id_departure')
            ->whereHas('hotel', function ($q) {
                $q->where('kota', 'Madinah');
            });
    }

    public function hotelTransitDetails()
    {
        return $this->hasMany(DepartureHotelDetail::class, 'id_departure', 'id_departure')
            ->whereHas('hotel', function ($q) {
                $q->where('kota', '!=', 'Mekkah')
                    ->where('kota', '!=', 'Madinah');
            });
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

    // ==========================================
    // PERLENGKAPAN RELATIONSHIPS
    // ==========================================

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

    // ==========================================
    // JENIS TRANSAKSI RELATIONSHIPS
    // ==========================================

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

    // ==========================================
    // PAKET TOUR HOTEL RELATIONSHIPS
    // ==========================================

    public function departurePaketTourHotels()
    {
        return $this->hasMany(DeparturePaketTourHotel::class, 'id_departure', 'id_departure');
    }

    public function paketTourHotels()
    {
        return $this->belongsToMany(Hotel::class, 'departure_paket_tour_hotels', 'id_departure', 'id_hotel')
            ->withPivot('id_paket_tour', 'urutan', 'harga_per_malam', 'durasi_menginap', 'jumlah_kamar', 'tipe_kamar', 'catatan')
            ->withTimestamps();
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

    public function getKeuntunganAttribute()
    {
        return ($this->total_pendapatan_bersih ?? 0) - ($this->total_pengeluaran ?? 0);
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
        return $this->harga_maskapai_domestik_berangkat ?
            'Rp ' . number_format($this->harga_maskapai_domestik_berangkat, 0, ',', '.') :
            'Rp 0';
    }

    public function getHargaMaskapaiDomestikPulangFormattedAttribute()
    {
        return $this->harga_maskapai_domestik_pulang ?
            'Rp ' . number_format($this->harga_maskapai_domestik_pulang, 0, ',', '.') :
            'Rp 0';
    }

    public function getHargaMaskapaiInternasionalBerangkatFormattedAttribute()
    {
        return $this->harga_maskapai_internasional_berangkat ?
            'Rp ' . number_format($this->harga_maskapai_internasional_berangkat, 0, ',', '.') :
            'Rp 0';
    }

    public function getHargaMaskapaiInternasionalPulangFormattedAttribute()
    {
        return $this->harga_maskapai_internasional_pulang ?
            'Rp ' . number_format($this->harga_maskapai_internasional_pulang, 0, ',', '.') :
            'Rp 0';
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
    // ACCESSORS - HOTEL (Dari DepartureHotelDetail)
    // ==========================================

    public function getTotalHargaHotelMekkahAttribute()
    {
        return $this->hotelMekkahDetails->sum(function ($detail) {
            return $detail->harga_per_malam * $detail->durasi_menginap * $detail->jumlah_kamar;
        });
    }

    public function getTotalHargaHotelMadinahAttribute()
    {
        return $this->hotelMadinahDetails->sum(function ($detail) {
            return $detail->harga_per_malam * $detail->durasi_menginap * $detail->jumlah_kamar;
        });
    }

    public function getTotalHargaHotelTransitAttribute()
    {
        return $this->hotelTransitDetails->sum(function ($detail) {
            return $detail->harga_per_malam * $detail->durasi_menginap * $detail->jumlah_kamar;
        });
    }

    public function getTotalHargaHotelAllAttribute()
    {
        return $this->total_harga_hotel_mekkah +
            $this->total_harga_hotel_madinah +
            $this->total_harga_hotel_transit;
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
        return $this->departurePerlengkapan->sum('total_harga');
    }

    public function getTotalHargaPerlengkapanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_perlengkapan, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - JENIS TRANSAKSI
    // ==========================================

    public function getTotalJenisTransaksiAttribute()
    {
        return $this->departureJenisTransaksis->sum('total_harga');
    }

    public function getTotalJenisTransaksiFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_jenis_transaksi, 0, ',', '.');
    }

    // ==========================================
    // ACCESSORS - PAKET TOUR HOTEL
    // ==========================================

    public function getTotalHargaPaketTourHotelAttribute()
    {
        return $this->departurePaketTourHotels->sum(function ($item) {
            return $item->total_harga;
        });
    }

    public function getTotalHargaPaketTourHotelFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_harga_paket_tour_hotel, 0, ',', '.');
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
                'departurePerlengkapan',
                'departureJenisTransaksis',
                'departurePaketTourHotels',
                'hotelMekkahDetails',
                'hotelMadinahDetails',
                'hotelTransitDetails',
            ]);

            // ==========================================
            // 1. TOTAL PENDAPATAN KOTOR (dari jamaah)
            // ==========================================
            $totalPendapatanKotor = 0;
            foreach ($this->jamaahs as $jamaah) {
                $totalPendapatanKotor += $jamaah->total_tagihan_setelah_diskon ?? 0;
            }
            $this->total_pendapatan_kotor = $totalPendapatanKotor;

            // ==========================================
            // 2. TOTAL DISKON (dari jamaah)
            // ==========================================
            $totalDiskon = 0;
            foreach ($this->jamaahs as $jamaah) {
                $totalDiskon += $jamaah->total_diskon ?? 0;
            }
            $this->total_diskon = $totalDiskon;

            // ==========================================
            // 3. TOTAL PENDAPATAN (kotor + diskon)
            // ==========================================
            $this->total_pendapatan = $totalPendapatanKotor + $totalDiskon;

            // ==========================================
            // 4. TOTAL PENDAPATAN BERSIH
            // ==========================================
            $this->total_pendapatan_bersih = $totalPendapatanKotor;

            // ==========================================
            // 5. TOTAL PENGELUARAN
            // ==========================================
            $totalPengeluaran = 0;

            // 5a. Harga Maskapai
            $totalPengeluaran += $this->harga_maskapai_domestik_berangkat ?? 0;
            $totalPengeluaran += $this->harga_maskapai_domestik_pulang ?? 0;
            $totalPengeluaran += $this->harga_maskapai_internasional_berangkat ?? 0;
            $totalPengeluaran += $this->harga_maskapai_internasional_pulang ?? 0;

            // 5b. Harga Hotel (Mekkah, Madinah, Transit)
            foreach ($this->hotelMekkahDetails as $detail) {
                $totalPengeluaran += $detail->total_harga;
            }
            foreach ($this->hotelMadinahDetails as $detail) {
                $totalPengeluaran += $detail->total_harga;
            }
            foreach ($this->hotelTransitDetails as $detail) {
                $totalPengeluaran += $detail->total_harga;
            }

            // 5c. Harga Hotel Tour (Paket Tour)
            foreach ($this->departurePaketTourHotels as $item) {
                $totalPengeluaran += $item->total_harga;
            }

            // 5d. Harga Perlengkapan
            foreach ($this->departurePerlengkapan as $perlengkapan) {
                $totalPengeluaran += $perlengkapan->total_harga ?? 0;
            }

            // 5e. Harga Jenis Transaksi
            foreach ($this->departureJenisTransaksis as $item) {
                $totalPengeluaran += $item->total_harga ?? 0;
            }

            // 5f. FEE AGENT
            foreach ($this->jamaahs as $jamaah) {
                $totalPengeluaran += $jamaah->fee_agent ?? 0;
            }

            $this->total_pengeluaran = $totalPengeluaran;

            // ==========================================
            // 6. KEUNTUNGAN
            // ==========================================
            $this->keuntungan = $totalPendapatanKotor - $totalPengeluaran;

            // ==========================================
            // 7. MARGIN LABA
            // ==========================================
            $this->margin_laba = $totalPendapatanKotor > 0
                ? round(($this->keuntungan / $totalPendapatanKotor) * 100, 2)
                : 0;

            // ==========================================
            // 8. UPDATE STATUS COMPLETION
            // ==========================================
            $this->is_jamaah_complete = $this->jamaahs()->count() > 0;
            $this->is_perlengkapan_complete = $this->departurePerlengkapan()->count() > 0;

            $this->save();
        });

        return $this;
    }

    public function calculatePengeluaran($totalJamaah = null)
    {
        if ($totalJamaah === null) {
            $totalJamaah = $this->jamaahs->count();
        }

        if ($totalJamaah == 0) {
            $totalJamaah = 1;
        }

        $totalPengeluaran = 0;

        // Pengeluaran dari jamaah (tiket domestik & international)
        foreach ($this->jamaahs as $jamaah) {
            $totalPengeluaran += ($jamaah->total_tiket_domestik ?? 0) +
                ($jamaah->total_tiket_international ?? 0);
        }

        // Maskapai
        $totalPengeluaran += ($this->harga_maskapai_domestik_berangkat ?? 0) +
            ($this->harga_maskapai_domestik_pulang ?? 0) +
            ($this->harga_maskapai_internasional_berangkat ?? 0) +
            ($this->harga_maskapai_internasional_pulang ?? 0);

        // Hotel
        $totalPengeluaran += $this->total_harga_hotel_mekkah +
            $this->total_harga_hotel_madinah +
            $this->total_harga_hotel_transit;

        // Perlengkapan
        $totalPengeluaran += $this->total_harga_perlengkapan;

        // Jenis Transaksi
        $totalPengeluaran += $this->total_jenis_transaksi;

        // Paket Tour Hotel
        $totalPengeluaran += $this->total_harga_paket_tour_hotel;

        return $totalPengeluaran;
    }

    // ==========================================
    // JAMAHA METHODS
    // ==========================================

    public function addJamaah($jamaahId, $catatan = null)
    {
        $exists = DepartureJamaah::where('id_departure', $this->id_departure)
            ->where('id_jamaah', $jamaahId)
            ->exists();

        if ($exists) {
            throw new \Exception('Jamaah sudah terdaftar di departure ini.');
        }

        if ($this->jamaah_terdaftar >= $this->kuota) {
            throw new \Exception('Kuota sudah penuh! (Kuota: ' . $this->kuota . ', Terdaftar: ' . $this->jamaah_terdaftar . ')');
        }

        // Tambahkan jamaah ke pivot
        $this->jamaahs()->attach($jamaahId, [
            'status_keberangkatan' => 'Terdaftar',
            'catatan' => $catatan,
        ]);

        // Update jamaah_terdaftar
        $this->increment('jamaah_terdaftar');

        // Recalculate
        $this->recalculate();

        return $this;
    }

    public function removeJamaah($jamaahId)
    {
        // Hapus dari pivot
        $this->jamaahs()->detach($jamaahId);

        // Update jamaah_terdaftar
        $this->decrement('jamaah_terdaftar');

        // Recalculate
        $this->recalculate();

        return $this;
    }

    public function scopeActive($query)
    {
        return $query->whereHas('statusKeberangkatan', function ($q) {
            $q->whereIn('nama_status', ['Aktif', 'Berangkat']);
        });
    }

    public function updateJamaahStatus($jamaahId, $status)
    {
        $this->jamaahs()->updateExistingPivot($jamaahId, [
            'status_keberangkatan' => $status
        ]);
        return $this;
    }

    // ==========================================
    // JENIS TRANSAKSI METHODS
    // ==========================================

    public function addMultipleJenisTransaksi(array $jenisTransaksiData)
    {
        $totalJamaah = $this->jamaahs->count();
        $added = [];

        foreach ($jenisTransaksiData as $data) {
            $jenisTransaksiId = $data['id_jenis_transaksi'];
            $hargaSatuan = $data['harga_satuan'] ?? 0;
            $catatan = $data['catatan'] ?? null;

            $exists = DepartureJenisTransaksi::where('id_departure', $this->id_departure)
                ->where('id_jenis_transaksi', $jenisTransaksiId)
                ->exists();

            if ($exists) continue;

            $totalHarga = $hargaSatuan * $totalJamaah;

            $departureJenisTransaksi = DepartureJenisTransaksi::create([
                'id_departure' => $this->id_departure,
                'id_jenis_transaksi' => $jenisTransaksiId,
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'catatan' => $catatan,
            ]);

            $added[] = $departureJenisTransaksi;
        }

        if (count($added) > 0) {
            $this->recalculate();
        }

        return $added;
    }

    public function removeJenisTransaksi($jenisTransaksiId)
    {
        DepartureJenisTransaksi::where('id_departure', $this->id_departure)
            ->where('id_jenis_transaksi', $jenisTransaksiId)
            ->delete();

        $this->recalculate();
        return $this;
    }

    public function updateJenisTransaksiHarga($jenisTransaksiId, $hargaSatuan)
    {
        $pivot = DepartureJenisTransaksi::where('id_departure', $this->id_departure)
            ->where('id_jenis_transaksi', $jenisTransaksiId)
            ->firstOrFail();

        $totalJamaah = $this->jamaahs->count();
        $pivot->update([
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $hargaSatuan * $totalJamaah,
        ]);

        $this->recalculate();
        return $this;
    }
}
