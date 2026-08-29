<?php

namespace App\Services;

use App\Models\Departure;
use App\Models\DepartureJamaah;
use App\Models\DepartureHotelDetail;
use App\Models\DeparturePerlengkapan;
use App\Models\DepartureJenisTransaksi;
use App\Models\DeparturePaketTourHotel;
use App\Models\PerlengkapanJamaah;
use App\Models\ProdukPaket;
use App\Models\Jamaah;
use App\Models\StatusKeberangkatan;
use App\Models\Maskapai;
use App\Models\Hotel;
use App\Models\Kamar;
use App\Models\Perlengkapan;
use App\Models\JenisTransaksi;
use Illuminate\Support\Facades\DB;

class DepartureService
{
    public function getAll(array $filters = [])
    {
        $query = Departure::with([
            'produk',
            'statusKeberangkatan',
            'maskapaiDomestikBerangkat',
            'maskapaiDomestikPulang',
            'maskapaiInternasionalBerangkat',
            'maskapaiInternasionalPulang',
            'hotelMekkah',
            'hotelMadinah',
            'hotelTransit',
            'jamaahs',
            'departurePerlengkapan.perlengkapan',
            'departureJenisTransaksis.jenisTransaksi',
            'departurePaketTourHotels.hotel'
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_keberangkatan', 'like', "%{$search}%")
                    ->orWhere('kode_keberangkatan', 'like', "%{$search}%")
                    ->orWhere('produk_paket', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('id_status', $filters['status']);
        }

        return $query->orderBy('tanggal_keberangkatan', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Departure::with([
            'produk',
            'produk.paketTour',
            'produk.paketTour.hotels',
            'produk.paketTour.hotels.kamars',
            'statusKeberangkatan',
            'maskapaiDomestikBerangkat',
            'maskapaiDomestikPulang',
            'maskapaiInternasionalBerangkat',
            'maskapaiInternasionalPulang',
            'hotelMekkah',
            'hotelMadinah',
            'hotelTransit',
            'jamaahs',
            'jamaahs.diskon',
            'hotelMekkahDetails',
            'hotelMadinahDetails',
            'hotelTransitDetails',
            'departurePerlengkapan.perlengkapan',
            'departurePerlengkapan.perlengkapanJamaahs.jamaah',
            'departureJenisTransaksis.jenisTransaksi',
            'departurePaketTourHotels.hotel',
            'departurePaketTourHotels.hotel.kamars',
            'departurePaketTourHotels.paketTour'
        ])->findOrFail($id);
    }

    // ==========================================
    // CREATE
    // ==========================================

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $produk = ProdukPaket::findOrFail($data['id_produk']);
            $data['produk_paket'] = $produk->nama_produk;

            $kodeProduk = $produk->kode_produk ?? 'PKT';
            $data['kode_keberangkatan'] = Departure::generateKode($kodeProduk);

            $data['is_maskapai_complete'] = false;
            $data['is_hotel_complete'] = false;
            $data['is_jamaah_complete'] = false;
            $data['is_catatan_complete'] = false;
            $data['is_perlengkapan_complete'] = false;

            $departure = Departure::create($data);

            return $departure->load([
                'statusKeberangkatan',
                'produk'
            ]);
        });
    }

    // ==========================================
    // UPDATE - Informasi Dasar
    // ==========================================

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = Departure::findOrFail($id);

            if (isset($data['id_produk']) && $data['id_produk'] != $departure->id_produk) {
                $produk = ProdukPaket::findOrFail($data['id_produk']);
                $data['produk_paket'] = $produk->nama_produk;
            }

            $departure->update($data);

            return $departure->load([
                'statusKeberangkatan',
                'produk'
            ]);
        });
    }

    // ==========================================
    // DELETE
    // ==========================================

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $departure = Departure::findOrFail($id);
            $nama = $departure->nama_keberangkatan;

            $departure->departureJamaahs()->delete();
            $departure->departurePerlengkapan()->delete();
            $departure->departureJenisTransaksis()->delete();
            $departure->hotelDetails()->delete();
            $departure->departurePaketTourHotels()->delete();

            $departure->delete();

            return $nama;
        });
    }

    // ==========================================
    // UPDATE MASKAPAI
    // ==========================================

    public function updateMaskapai($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = $this->getById($id);
            $departure->update($data);

            $isComplete = $departure->id_maskapai_domestik_berangkat &&
                $departure->id_maskapai_domestik_pulang &&
                $departure->id_maskapai_internasional_berangkat &&
                $departure->id_maskapai_internasional_pulang;

            $departure->is_maskapai_complete = $isComplete;
            $departure->save();
            $departure->recalculate();

            return $departure->load([
                'maskapaiDomestikBerangkat',
                'maskapaiDomestikPulang',
                'maskapaiInternasionalBerangkat',
                'maskapaiInternasionalPulang'
            ]);
        });
    }

    // ==========================================
    // UPDATE HOTEL
    // ==========================================

    public function updateHotel($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = $this->getById($id);

            $departure->update([
                'id_hotel_mekkah' => $data['id_hotel_mekkah'] ?? null,
                'id_hotel_madinah' => $data['id_hotel_madinah'] ?? null,
                'id_hotel_transit' => $data['id_hotel_transit'] ?? null,
            ]);

            // Hapus data lama di departure_hotel_details
            DepartureHotelDetail::where('id_departure', $id)->delete();

            if (!empty($data['kamar_ids'])) {
                foreach ($data['kamar_ids'] as $kamarId) {
                    $kamar = Kamar::find($kamarId);
                    if ($kamar) {
                        $idHotel = null;
                        if ($departure->id_hotel_mekkah && $kamar->id_hotel == $departure->id_hotel_mekkah) {
                            $idHotel = $departure->id_hotel_mekkah;
                        } elseif ($departure->id_hotel_madinah && $kamar->id_hotel == $departure->id_hotel_madinah) {
                            $idHotel = $departure->id_hotel_madinah;
                        } elseif ($departure->id_hotel_transit && $kamar->id_hotel == $departure->id_hotel_transit) {
                            $idHotel = $departure->id_hotel_transit;
                        }

                        if ($idHotel) {
                            // Ambil harga dari request
                            $hargaPerMalam = isset($data['kamar_harga'][$kamarId])
                                ? (int) $data['kamar_harga'][$kamarId]
                                : 0;

                            DepartureHotelDetail::create([
                                'id_departure' => $id,
                                'id_hotel' => $idHotel,
                                'id_kamar' => $kamarId,
                                'tipe_kamar' => $kamar->tipe_kamar,
                                'jumlah_kamar' => $data['kamar_jumlah'][$kamarId] ?? 1,
                                'harga_per_malam' => $hargaPerMalam,
                                'durasi_menginap' => $data['kamar_durasi'][$kamarId] ?? 1,
                                'catatan' => $data['kamar_catatan'][$kamarId] ?? null,
                            ]);
                        }
                    }
                }
            }

            $isComplete = $departure->id_hotel_mekkah &&
                $departure->id_hotel_madinah &&
                $departure->id_hotel_transit;

            $departure->is_hotel_complete = $isComplete;
            $departure->save();
            $departure->recalculate();

            return $departure->load([
                'hotelMekkah',
                'hotelMadinah',
                'hotelTransit',
                'hotelMekkahDetails',
                'hotelMadinahDetails',
                'hotelTransitDetails'
            ]);
        });
    }

    // ==========================================
    // UPDATE JAMAAH
    // ==========================================

    public function updateJamaah($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = $this->getById($id);

            if (isset($data['jamaah_ids'])) {
                $currentJamaahs = $departure->jamaahs->pluck('id_jamaah')->toArray();
                $newJamaahs = $data['jamaah_ids'];

                // Hapus jamaah yang tidak ada di list baru
                $toRemove = array_diff($currentJamaahs, $newJamaahs);
                foreach ($toRemove as $jamaahId) {
                    $departure->removeJamaah($jamaahId);
                }

                // Tambah jamaah baru
                $toAdd = array_diff($newJamaahs, $currentJamaahs);
                foreach ($toAdd as $jamaahId) {
                    try {
                        $departure->addJamaah($jamaahId);
                    } catch (\Exception $e) {
                        // Skip jika sudah ada
                    }
                }
            }

            $isComplete = $departure->jamaahs()->count() > 0;
            $departure->is_jamaah_complete = $isComplete;
            $departure->save();

            $departure->recalculate();

            return $departure->load([
                'jamaahs',
                'jamaahs.diskon'
            ]);
        });
    }

    // ==========================================
    // UPDATE CATATAN
    // ==========================================

    public function updateCatatan($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = $this->getById($id);
            $departure->update(['catatan' => $data['catatan'] ?? null]);

            $departure->is_catatan_complete = !empty($departure->catatan);
            $departure->save();

            return $departure;
        });
    }

    // ==========================================
    // PERLENGKAPAN
    // ==========================================

    public function addMultiplePerlengkapanToDeparture($departureId, array $perlengkapanIds, array $data = [])
    {
        return DB::transaction(function () use ($departureId, $perlengkapanIds, $data) {
            $departure = $this->getById($departureId);
            $added = [];
            $addedNames = [];

            foreach ($perlengkapanIds as $perlengkapanId) {
                $perlengkapan = Perlengkapan::find($perlengkapanId);
                if (!$perlengkapan) continue;

                $exists = DeparturePerlengkapan::where('id_departure', $departureId)
                    ->where('id_perlengkapan', $perlengkapanId)
                    ->exists();

                if ($exists) continue;

                $jumlahPerJamaah = $data['jumlah_per_jamaah'] ?? 1;
                $hargaSatuan = $perlengkapan->harga_satuan;
                $totalJamaah = $departure->jamaahs->count();
                $totalHarga = $hargaSatuan * $jumlahPerJamaah * $totalJamaah;

                $departurePerlengkapan = DeparturePerlengkapan::create([
                    'id_departure' => $departureId,
                    'id_perlengkapan' => $perlengkapanId,
                    'jumlah_per_jamaah' => $jumlahPerJamaah,
                    'harga_satuan' => $hargaSatuan,
                    'total_harga' => $totalHarga,
                    'keterangan' => $data['keterangan'] ?? null,
                    'is_active' => true,
                ]);

                $jamaahs = $departure->jamaahs;
                foreach ($jamaahs as $jamaah) {
                    PerlengkapanJamaah::create([
                        'id_jamaah' => $jamaah->id_jamaah,
                        'id_departure_perlengkapan' => $departurePerlengkapan->id,
                        'jumlah' => $jumlahPerJamaah,
                        'harga_satuan' => $hargaSatuan,
                        'total_harga' => $hargaSatuan * $jumlahPerJamaah,
                        'status_terima' => 'Belum Diterima',
                        'keterangan' => $data['keterangan'] ?? null,
                    ]);
                }

                $added[] = $departurePerlengkapan;
                $addedNames[] = $perlengkapan->nama_perlengkapan;
            }

            if (count($added) > 0) {
                $departure->is_perlengkapan_complete = true;
                $departure->save();
                $departure->recalculate();
            }

            return [
                'departure' => $departure,
                'added' => $added,
                'addedNames' => $addedNames,
                'count' => count($added)
            ];
        });
    }

    public function removePerlengkapanFromDeparture($departureId, $departurePerlengkapanId)
    {
        return DB::transaction(function () use ($departureId, $departurePerlengkapanId) {
            $departurePerlengkapan = DeparturePerlengkapan::where('id_departure', $departureId)
                ->where('id', $departurePerlengkapanId)
                ->firstOrFail();

            $departurePerlengkapan->perlengkapanJamaahs()->delete();
            $departurePerlengkapan->delete();

            $departure = $this->getById($departureId);

            $count = $departure->departurePerlengkapan()->count();
            if ($count == 0) {
                $departure->is_perlengkapan_complete = false;
                $departure->save();
            }

            $departure->recalculate();

            return true;
        });
    }

    public function togglePerlengkapanStatus($departureId, $departurePerlengkapanId)
    {
        return DB::transaction(function () use ($departureId, $departurePerlengkapanId) {
            $departurePerlengkapan = DeparturePerlengkapan::where('id_departure', $departureId)
                ->where('id', $departurePerlengkapanId)
                ->firstOrFail();
            $departurePerlengkapan->is_active = !$departurePerlengkapan->is_active;
            $departurePerlengkapan->save();

            return $departurePerlengkapan;
        });
    }

    public function updatePerlengkapanStatusJamaah($departurePerlengkapanId, $jamaahId, $status)
    {
        return DB::transaction(function () use ($departurePerlengkapanId, $jamaahId, $status) {
            $perlengkapanJamaah = PerlengkapanJamaah::where('id_departure_perlengkapan', $departurePerlengkapanId)
                ->where('id_jamaah', $jamaahId)
                ->firstOrFail();
            $perlengkapanJamaah->status_terima = $status;
            $perlengkapanJamaah->save();

            return $perlengkapanJamaah;
        });
    }

    public function getPerlengkapanOptionsForDeparture($departureId)
    {
        $existingIds = DeparturePerlengkapan::where('id_departure', $departureId)
            ->pluck('id_perlengkapan')
            ->toArray();

        return Perlengkapan::whereNotIn('id_perlengkapan', $existingIds)
            ->orderBy('nama_perlengkapan')
            ->get();
    }

    public function getPerlengkapanByDeparture($departureId)
    {
        return DeparturePerlengkapan::with(['perlengkapan', 'perlengkapanJamaahs.jamaah'])
            ->where('id_departure', $departureId)
            ->get();
    }

    // ==========================================
    // JENIS TRANSAKSI
    // ==========================================

    public function getJenisTransaksiOptions()
    {
        return JenisTransaksi::orderBy('nama')->get();
    }

    public function getAvailableJenisTransaksi($departureId)
    {
        $existingIds = DepartureJenisTransaksi::where('id_departure', $departureId)
            ->pluck('id_jenis_transaksi')
            ->toArray();

        return JenisTransaksi::whereNotIn('id_jenis', $existingIds)
            ->orderBy('nama')
            ->get();
    }

    public function getJenisTransaksiByDeparture($departureId)
    {
        return DepartureJenisTransaksi::with('jenisTransaksi')
            ->where('id_departure', $departureId)
            ->get();
    }

    public function addMultipleJenisTransaksiToDeparture($departureId, array $jenisTransaksiData)
    {
        return DB::transaction(function () use ($departureId, $jenisTransaksiData) {
            $departure = $this->getById($departureId);
            $added = $departure->addMultipleJenisTransaksi($jenisTransaksiData);

            return [
                'departure' => $departure,
                'added' => $added,
                'count' => count($added)
            ];
        });
    }

    public function removeJenisTransaksiFromDeparture($departureId, $jenisTransaksiId)
    {
        return DB::transaction(function () use ($departureId, $jenisTransaksiId) {
            $departure = $this->getById($departureId);
            $departure->removeJenisTransaksi($jenisTransaksiId);
            return $departure;
        });
    }

    public function updateJenisTransaksiHarga($departureId, $jenisTransaksiId, $hargaSatuan)
    {
        return DB::transaction(function () use ($departureId, $jenisTransaksiId, $hargaSatuan) {
            $departure = $this->getById($departureId);
            $departure->updateJenisTransaksiHarga($jenisTransaksiId, $hargaSatuan);
            return $departure;
        });
    }

    // ==========================================
    // SYNC METHODS
    // ==========================================

    public function syncAllDepartureData($id)
    {
        return DB::transaction(function () use ($id) {
            $departure = $this->getById($id);
            $jamaahCount = $departure->jamaahs->count();

            if ($jamaahCount == 0) {
                throw new \Exception('Tidak ada jamaah terdaftar. Tambahkan jamaah terlebih dahulu.');
            }

            // 1. Sinkronisasi Perlengkapan
            $perlengkapanList = DeparturePerlengkapan::where('id_departure', $id)->get();
            foreach ($perlengkapanList as $perlengkapan) {
                // Update total harga berdasarkan jumlah jamaah terbaru
                $perlengkapan->total_harga = $perlengkapan->harga_satuan * $perlengkapan->jumlah_per_jamaah * $jamaahCount;
                $perlengkapan->save();

                // Update PerlengkapanJamaah untuk setiap jamaah
                $existingJamaahIds = $perlengkapan->perlengkapanJamaahs->pluck('id_jamaah')->toArray();
                $currentJamaahIds = $departure->jamaahs->pluck('id_jamaah')->toArray();

                // Tambahkan untuk jamaah baru yang belum punya perlengkapan ini
                $newJamaahIds = array_diff($currentJamaahIds, $existingJamaahIds);
                foreach ($newJamaahIds as $jamaahId) {
                    PerlengkapanJamaah::create([
                        'id_jamaah' => $jamaahId,
                        'id_departure_perlengkapan' => $perlengkapan->id,
                        'jumlah' => $perlengkapan->jumlah_per_jamaah,
                        'harga_satuan' => $perlengkapan->harga_satuan,
                        'total_harga' => $perlengkapan->harga_satuan * $perlengkapan->jumlah_per_jamaah,
                        'status_terima' => 'Belum Diterima',
                        'keterangan' => $perlengkapan->keterangan,
                    ]);
                }

                // Hapus PerlengkapanJamaah untuk jamaah yang sudah tidak ada di departure
                $removedJamaahIds = array_diff($existingJamaahIds, $currentJamaahIds);
                if (!empty($removedJamaahIds)) {
                    PerlengkapanJamaah::where('id_departure_perlengkapan', $perlengkapan->id)
                        ->whereIn('id_jamaah', $removedJamaahIds)
                        ->delete();
                }
            }

            // 2. Sinkronisasi Jenis Transaksi
            $jenisTransaksiList = DepartureJenisTransaksi::where('id_departure', $id)->get();
            foreach ($jenisTransaksiList as $item) {
                // Update total harga berdasarkan jumlah jamaah terbaru
                $item->total_harga = $item->harga_satuan * $jamaahCount;
                $item->save();
            }

            // 3. Recalculate semua data keuangan
            $departure->recalculate();

            // 4. Update status completion
            $departure->is_perlengkapan_complete = $departure->departurePerlengkapan()->count() > 0;
            $departure->is_jamaah_complete = $jamaahCount > 0;
            $departure->save();

            return $departure->fresh();
        });
    }

    public function getAvailableJamaahsForSync($departureId)
    {
        $departure = Departure::find($departureId);

        if (!$departure) {
            return collect();
        }

        $subquery = DepartureJamaah::select('id_jamaah')
            ->join('departures', 'departures.id_departure', '=', 'departure_jamaahs.id_departure')
            ->join('status_keberangkatans', 'status_keberangkatans.id_status', '=', 'departures.id_status')
            ->whereIn('status_keberangkatans.nama_status', ['Aktif', 'Berangkat']);

        // Hanya jamaah yang sudah lunas
        $query = Jamaah::whereNotIn('id_jamaah', $subquery)
            ->where('status_pembayaran', 'Lunas')
            ->where('bulan_keberangkatan', $departure->bulan_keberangkatan)
            ->where('tahun_keberangkatan', $departure->tahun_keberangkatan);

        return $query->orderBy('nama_lengkap')->get();
    }

    public function syncJamaahsForDeparture($departureId)
    {
        return DB::transaction(function () use ($departureId) {
            $departure = $this->getById($departureId);

            // Ambil semua jamaah yang memenuhi kriteria (lunas, bulan/tahun sesuai, belum terdaftar di departure aktif lain)
            $availableJamaahs = $this->getAvailableJamaahs($departureId);

            // Dapatkan ID jamaah yang sudah terdaftar di departure ini
            $currentJamaahIds = $departure->jamaahs->pluck('id_jamaah')->toArray();

            // Cari jamaah baru yang tersedia tapi belum terdaftar
            $newJamaahIds = $availableJamaahs->pluck('id_jamaah')->toArray();
            $toAdd = array_diff($newJamaahIds, $currentJamaahIds);

            // Tambahkan jamaah baru
            $addedCount = 0;
            $addedNames = [];
            foreach ($toAdd as $jamaahId) {
                try {
                    $jamaah = Jamaah::find($jamaahId);
                    $departure->addJamaah($jamaahId);
                    $addedCount++;
                    $addedNames[] = $jamaah->nama_lengkap;
                } catch (\Exception $e) {
                    // Skip jika gagal
                }
            }

            // Update status completion
            $departure->is_jamaah_complete = $departure->jamaahs()->count() > 0;
            $departure->save();

            // Recalculate
            $departure->recalculate();

            return [
                'departure' => $departure,
                'added_count' => $addedCount,
                'added_names' => $addedNames,
                'total_jamaahs' => $departure->jamaahs->count()
            ];
        });
    }

    // ==========================================
    // GET AVAILABLE JAMAAH (Filter by Month/Year)
    // ==========================================

    public function getAvailableJamaahs($departureId = null)
    {
        $departure = null;
        if ($departureId) {
            $departure = Departure::find($departureId);
        }

        $subquery = DepartureJamaah::select('id_jamaah')
            ->join('departures', 'departures.id_departure', '=', 'departure_jamaahs.id_departure')
            ->join('status_keberangkatans', 'status_keberangkatans.id_status', '=', 'departures.id_status')
            ->whereIn('status_keberangkatans.nama_status', ['Aktif', 'Berangkat']);

        if ($departureId) {
            $subquery->where('departures.id_departure', '!=', $departureId);
        }

        // Hanya jamaah yang sudah lunas
        $query = Jamaah::whereNotIn('id_jamaah', $subquery)
            ->where('status_pembayaran', 'Lunas');

        // Filter berdasarkan bulan dan tahun keberangkatan departure
        if ($departure) {
            $query->where('bulan_keberangkatan', $departure->bulan_keberangkatan)
                ->where('tahun_keberangkatan', $departure->tahun_keberangkatan);
        }

        return $query->orderBy('nama_lengkap')->get();
    }

    public function getJamaahsByProduk($produkId, $departureId = null)
    {
        $produk = ProdukPaket::find($produkId);
        if (!$produk) {
            return collect();
        }

        $departure = null;
        if ($departureId) {
            $departure = Departure::find($departureId);
        }

        $subquery = DepartureJamaah::select('id_jamaah')
            ->join('departures', 'departures.id_departure', '=', 'departure_jamaahs.id_departure')
            ->join('status_keberangkatans', 'status_keberangkatans.id_status', '=', 'departures.id_status')
            ->whereIn('status_keberangkatans.nama_status', ['Aktif', 'Berangkat']);

        if ($departureId) {
            $subquery->where('departures.id_departure', '!=', $departureId);
        }

        $query = Jamaah::where('produk_paket', $produk->nama_produk)
            ->whereNotIn('id_jamaah', $subquery)
            ->where('status_pembayaran', 'Lunas');

        if ($departure) {
            $query->where('bulan_keberangkatan', $departure->bulan_keberangkatan)
                ->where('tahun_keberangkatan', $departure->tahun_keberangkatan);
        }

        return $query->orderBy('nama_lengkap')->get();
    }

    public function getProdukOptions()
    {
        return ProdukPaket::where('is_active', true)
            ->orderBy('nama_produk')
            ->get();
    }

    public function getStatusOptions()
    {
        return StatusKeberangkatan::orderBy('nama_status')->get();
    }

    public function getMaskapaiOptions()
    {
        return Maskapai::with('tipePenerbangan')
            ->orderBy('nama_maskapai')
            ->get();
    }

    public function getHotelOptions()
    {
        return Hotel::orderBy('nama_hotel')->get();
    }

    // ==========================================
    // RECALCULATE METHODS
    // ==========================================

    public function recalculateAll()
    {
        $departures = Departure::all();
        foreach ($departures as $departure) {
            $departure->recalculate();
        }
        return $departures;
    }

    // ==========================================
    // UPDATE STATUS
    // ==========================================

    public function updateStatus($id, $statusId)
    {
        return DB::transaction(function () use ($id, $statusId) {
            $departure = $this->getById($id);
            $departure->update(['id_status' => $statusId]);
            return $departure->load('statusKeberangkatan');
        });
    }

    // ==========================================
    // ADD/REMOVE JAMAAH (Single)
    // ==========================================

    public function addJamaah($departureId, $jamaahId, $catatan = null)
    {
        return DB::transaction(function () use ($departureId, $jamaahId, $catatan) {
            $departure = $this->getById($departureId);
            $departure->addJamaah($jamaahId, $catatan);
            return $departure;
        });
    }

    public function removeJamaah($departureId, $jamaahId)
    {
        return DB::transaction(function () use ($departureId, $jamaahId) {
            $departure = $this->getById($departureId);
            $departure->removeJamaah($jamaahId);
            return $departure;
        });
    }

    // ==========================================
    // PAKET TOUR HOTEL
    // ==========================================

    public function updatePaketTourHotel($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = $this->getById($id);

            // Hapus data lama
            DeparturePaketTourHotel::where('id_departure', $id)->delete();

            if (!empty($data['paket_tour_hotels'])) {
                foreach ($data['paket_tour_hotels'] as $hotelData) {
                    // Skip jika checkbox tidak dicentang (tidak ada id_hotel)
                    if (empty($hotelData['id_hotel'])) {
                        continue;
                    }

                    DeparturePaketTourHotel::create([
                        'id_departure' => $id,
                        'id_paket_tour' => $data['id_paket_tour'],
                        'id_hotel' => $hotelData['id_hotel'],
                        'urutan' => $hotelData['urutan'] ?? 0,
                        'harga_per_malam' => $hotelData['harga_per_malam'] ?? 0,
                        'durasi_menginap' => $hotelData['durasi_menginap'] ?? 1,
                        'jumlah_kamar' => $hotelData['jumlah_kamar'] ?? 1,
                        'tipe_kamar' => $hotelData['tipe_kamar'] ?? null,
                        'catatan' => $hotelData['catatan'] ?? null,
                    ]);
                }
            }

            $departure->recalculate();

            return $departure->load([
                'departurePaketTourHotels.hotel',
                'departurePaketTourHotels.paketTour'
            ]);
        });
    }

    public function getPaketTourHotelsByDeparture($id)
    {
        return DeparturePaketTourHotel::with(['hotel', 'paketTour'])
            ->where('id_departure', $id)
            ->orderBy('urutan')
            ->get();
    }
}
