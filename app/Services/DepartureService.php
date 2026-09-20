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
use App\Models\DepartureJenisTransaksiJamaah;
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
            'departureJenisTransaksis.departureJenisTransaksiJamaahs.jamaah',
            'departurePaketTourHotels.hotel',
            'departurePaketTourHotels.hotel.kamars',
            'departurePaketTourHotels.kamar',
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

                $toRemove = array_diff($currentJamaahs, $newJamaahs);
                foreach ($toRemove as $jamaahId) {
                    $departure->removeJamaah($jamaahId);
                }

                $toAdd = array_diff($newJamaahs, $currentJamaahs);
                foreach ($toAdd as $jamaahId) {
                    try {
                        $departure->addJamaah($jamaahId);
                    } catch (\Exception $e) {
                        // Skip
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

        // ✅ RECALCULATE setelah update status
        $departurePerlengkapan = $perlengkapanJamaah->departurePerlengkapan;
        if ($departurePerlengkapan && $departurePerlengkapan->departure) {
            $departurePerlengkapan->departure->recalculate();
        }

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
        $totalJamaah = $departure->jamaahs->count();

        if ($totalJamaah == 0) {
            throw new \Exception('Tidak ada jamaah terdaftar.');
        }

        $added = [];

        foreach ($jenisTransaksiData as $data) {
            $jenisTransaksiId = $data['id_jenis_transaksi'];
            $hargaTotal = $data['harga_total'] ?? 0;
            $catatan = $data['catatan'] ?? null;

            $exists = DepartureJenisTransaksi::where('id_departure', $departureId)
                ->where('id_jenis_transaksi', $jenisTransaksiId)
                ->exists();

            if ($exists) continue;

            $hargaSatuan = round($hargaTotal / $totalJamaah);

            $departureJenisTransaksi = DepartureJenisTransaksi::create([
                'id_departure' => $departureId,
                'id_jenis_transaksi' => $jenisTransaksiId,
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $hargaTotal,
                'catatan' => $catatan,
            ]);

            // ✅ Buat record per jamaah
            foreach ($departure->jamaahs as $jamaah) {
                DepartureJenisTransaksiJamaah::create([
                    'id_departure_jenis_transaksi' => $departureJenisTransaksi->id,
                    'id_jamaah' => $jamaah->id_jamaah,
                    'jumlah' => 1,
                    'harga_satuan' => $hargaSatuan,
                    'total_harga' => $hargaSatuan,
                    'status_terima' => 'Belum Diterima',
                    'keterangan' => $catatan,
                ]);
            }

            $added[] = $departureJenisTransaksi;
        }

        if (count($added) > 0) {
            $departure->recalculate();
        }

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
        $departureJenisTransaksi = DepartureJenisTransaksi::where('id_departure', $departureId)
            ->where('id_jenis_transaksi', $jenisTransaksiId)
            ->first();

        if ($departureJenisTransaksi) {
            $departureJenisTransaksi->departureJenisTransaksiJamaahs()->delete();
            $departureJenisTransaksi->delete();
        }

        $departure = $this->getById($departureId);
        $departure->recalculate();
        return $departure;
    });
}
public function updateJenisTransaksiHarga($departureId, $jenisTransaksiId, $hargaTotal)
{
    return DB::transaction(function () use ($departureId, $jenisTransaksiId, $hargaTotal) {
        $departure = $this->getById($departureId);
        $totalJamaah = $departure->jamaahs->count();

        if ($totalJamaah == 0) {
            throw new \Exception('Tidak ada jamaah terdaftar.');
        }

        $pivot = DepartureJenisTransaksi::where('id_departure', $departureId)
            ->where('id_jenis_transaksi', $jenisTransaksiId)
            ->firstOrFail();

        $hargaSatuan = round($hargaTotal / $totalJamaah);

        $pivot->update([
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $hargaTotal,
        ]);

        // ✅ Update harga di record per jamaah
        $pivot->departureJenisTransaksiJamaahs()->update([
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $hargaSatuan,
        ]);

        $departure->recalculate();
        return $departure;
    });
}

/**
 * Toggle status terima jenis transaksi per jamaah
 */
public function updateJenisTransaksiStatusJamaah($departureJenisTransaksiId, $jamaahId, $status)
{
    return DB::transaction(function () use ($departureJenisTransaksiId, $jamaahId, $status) {
        $record = DepartureJenisTransaksiJamaah::where('id_departure_jenis_transaksi', $departureJenisTransaksiId)
            ->where('id_jamaah', $jamaahId)
            ->firstOrFail();

        $record->status_terima = $status;
        $record->save();

        // Recalculate departure
        $departureJenisTransaksi = $record->departureJenisTransaksi;
        if ($departureJenisTransaksi && $departureJenisTransaksi->departure) {
            $departureJenisTransaksi->departure->recalculate();
        }

        return $record;
    });
}

/**
 * Get detail jenis transaksi dengan daftar jamaah
 */
public function getJenisTransaksiDetail($departureJenisTransaksiId)
{
    return DepartureJenisTransaksi::with([
        'jenisTransaksi',
        'departureJenisTransaksiJamaahs.jamaah',
        'departure',
    ])->findOrFail($departureJenisTransaksiId);
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
            throw new \Exception(
                'Tidak ada jamaah terdaftar. Tambahkan jamaah terlebih dahulu.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sinkronisasi Perlengkapan
        |--------------------------------------------------------------------------
        */

        $perlengkapanList = DeparturePerlengkapan::where(
            'id_departure',
            $id
        )->get();

        foreach ($perlengkapanList as $perlengkapan) {
            // Hitung total harga perlengkapan
            $perlengkapan->total_harga =
                $perlengkapan->harga_satuan
                * $perlengkapan->jumlah_per_jamaah
                * $jamaahCount;

            $perlengkapan->save();

            // Jamaah yang sudah memiliki perlengkapan
            $existingJamaahIds = $perlengkapan
                ->perlengkapanJamaahs
                ->pluck('id_jamaah')
                ->toArray();

            // Jamaah yang saat ini terdaftar pada departure
            $currentJamaahIds = $departure
                ->jamaahs
                ->pluck('id_jamaah')
                ->toArray();

            // Cari jamaah baru
            $newJamaahIds = array_diff(
                $currentJamaahIds,
                $existingJamaahIds
            );

            // Tambahkan perlengkapan untuk jamaah baru
            foreach ($newJamaahIds as $jamaahId) {
                PerlengkapanJamaah::create([
                    'id_jamaah' => $jamaahId,
                    'id_departure_perlengkapan' => $perlengkapan->id,
                    'jumlah' => $perlengkapan->jumlah_per_jamaah,
                    'harga_satuan' => $perlengkapan->harga_satuan,
                    'total_harga' =>
                        $perlengkapan->harga_satuan
                        * $perlengkapan->jumlah_per_jamaah,
                    'status_terima' => 'Belum Diterima',
                    'keterangan' => $perlengkapan->keterangan,
                ]);
            }

            // Cari jamaah yang sudah tidak terdaftar
            $removedJamaahIds = array_diff(
                $existingJamaahIds,
                $currentJamaahIds
            );

            // Hapus perlengkapan jamaah yang sudah tidak terdaftar
            if (!empty($removedJamaahIds)) {
                PerlengkapanJamaah::where(
                    'id_departure_perlengkapan',
                    $perlengkapan->id
                )
                    ->whereIn('id_jamaah', $removedJamaahIds)
                    ->delete();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sinkronisasi Jenis Transaksi
        |--------------------------------------------------------------------------
        */

        $jenisTransaksiList = DepartureJenisTransaksi::where(
            'id_departure',
            $id
        )->get();

        foreach ($jenisTransaksiList as $item) {

            /*
             * Total harga tetap.
             * Harga satuan dihitung ulang berdasarkan jumlah jamaah.
             */
            $item->harga_satuan = $jamaahCount > 0
                ? round($item->total_harga / $jamaahCount)
                : 0;

            $item->save();

            /*
             * Ambil ID jamaah yang sudah memiliki
             * detail jenis transaksi.
             */
            $existingJamaahIds = $item
                ->departureJenisTransaksiJamaahs
                ->pluck('id_jamaah')
                ->toArray();

            /*
             * Ambil ID jamaah yang saat ini terdaftar
             * pada departure.
             */
            $currentJamaahIds = $departure
                ->jamaahs
                ->pluck('id_jamaah')
                ->toArray();

            /*
             * Cari jamaah baru.
             */
            $newJamaahIds = array_diff(
                $currentJamaahIds,
                $existingJamaahIds
            );

            /*
             * Buat detail transaksi untuk jamaah baru.
             */
            foreach ($newJamaahIds as $jamaahId) {
                DepartureJenisTransaksiJamaah::create([
                    'id_departure_jenis_transaksi' => $item->id,
                    'id_jamaah' => $jamaahId,
                    'jumlah' => 1,
                    'harga_satuan' => $item->harga_satuan,
                    'total_harga' => $item->harga_satuan,
                    'status_terima' => 'Belum Diterima',
                    'keterangan' => $item->catatan,
                ]);
            }

            /*
             * Update harga untuk jamaah yang sudah ada.
             */
            $item->departureJenisTransaksiJamaahs()->update([
                'harga_satuan' => $item->harga_satuan,
                'total_harga' => $item->harga_satuan,
            ]);

            /*
             * Cari jamaah yang sudah tidak terdaftar.
             */
            $removedJamaahIds = array_diff(
                $existingJamaahIds,
                $currentJamaahIds
            );

            /*
             * Hapus detail transaksi jamaah yang
             * sudah tidak terdaftar.
             */
            if (!empty($removedJamaahIds)) {
                $item->departureJenisTransaksiJamaahs()
                    ->whereIn('id_jamaah', $removedJamaahIds)
                    ->delete();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Recalculate Departure
        |--------------------------------------------------------------------------
        */

        $departure->recalculate();

        /*
        |--------------------------------------------------------------------------
        | Update Status Kelengkapan
        |--------------------------------------------------------------------------
        */

        $departure->is_perlengkapan_complete =
            $departure->departurePerlengkapan()->count() > 0;

        $departure->is_jamaah_complete =
            $jamaahCount > 0;

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
            $availableJamaahs = $this->getAvailableJamaahs($departureId);
            $currentJamaahIds = $departure->jamaahs->pluck('id_jamaah')->toArray();
            $newJamaahIds = $availableJamaahs->pluck('id_jamaah')->toArray();
            $toAdd = array_diff($newJamaahIds, $currentJamaahIds);

            $addedCount = 0;
            $addedNames = [];
            foreach ($toAdd as $jamaahId) {
                try {
                    $jamaah = Jamaah::find($jamaahId);
                    $departure->addJamaah($jamaahId);
                    $addedCount++;
                    $addedNames[] = $jamaah->nama_lengkap;
                } catch (\Exception $e) {
                    // Skip
                }
            }

            $departure->is_jamaah_complete = $departure->jamaahs()->count() > 0;
            $departure->save();
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
    // GET AVAILABLE JAMAAH (HANYA LUNAS)
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

        $query = Jamaah::whereNotIn('id_jamaah', $subquery)
            ->where('status_pembayaran', 'Lunas');

        if ($departure) {
            $query->where('bulan_keberangkatan', $departure->bulan_keberangkatan)
                ->where('tahun_keberangkatan', $departure->tahun_keberangkatan);
        }

        return $query->orderBy('nama_lengkap')->get();
    }

    // ==========================================
    // GET ALL JAMAAH FOR SELECTION (SEMUA)
    // ==========================================

    public function getAllJamaahsForSelection($departureId = null)
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

        $query = Jamaah::whereNotIn('id_jamaah', $subquery);

        if ($departure) {
            $query->where(function ($q) use ($departure) {
                $q->where(function ($subQ) use ($departure) {
                    $subQ->where('bulan_keberangkatan', $departure->bulan_keberangkatan)
                        ->where('tahun_keberangkatan', $departure->tahun_keberangkatan);
                })->orWhereNull('bulan_keberangkatan')
                    ->orWhereNull('tahun_keberangkatan');
            });
        }

        return $query->orderByRaw("CASE WHEN status_pembayaran = 'Lunas' THEN 0 ELSE 1 END")
            ->orderBy('nama_lengkap')
            ->get();
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
        return ProdukPaket::where('is_active', true)->orderBy('nama_produk')->get();
    }

    public function getStatusOptions()
    {
        return StatusKeberangkatan::orderBy('nama_status')->get();
    }

    public function getMaskapaiOptions()
    {
        return Maskapai::with('tipePenerbangan')->orderBy('nama_maskapai')->get();
    }

    public function getHotelOptions()
    {
        return Hotel::orderBy('nama_hotel')->get();
    }

    // ==========================================
    // RECALCULATE
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
    // ADD/REMOVE JAMAAH
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
    // PAKET TOUR HOTEL (UPDATED — Support Multiple Kamar)
    // ==========================================

    public function updatePaketTourHotel($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $departure = $this->getById($id);

            // Hapus data lama
            DeparturePaketTourHotel::where('id_departure', $id)->delete();

            if (!empty($data['paket_tour_hotels'])) {
                foreach ($data['paket_tour_hotels'] as $hotelData) {
                    // Skip jika checkbox hotel tidak dicentang
                    if (empty($hotelData['id_hotel'])) {
                        continue;
                    }

                    // CEK: ada tipe kamar yang dipilih (multiple)
                    if (!empty($hotelData['tipe_kamar_ids']) && is_array($hotelData['tipe_kamar_ids'])) {
                        foreach ($hotelData['tipe_kamar_ids'] as $kamarId) {
                            $kamar = Kamar::find($kamarId);
                            if (!$kamar) continue;

                            $jumlahKamar = $hotelData['kamar_jumlah'][$kamarId] ?? 1;
                            $hargaPerMalam = isset($hotelData['kamar_harga'][$kamarId])
                                ? (int) $hotelData['kamar_harga'][$kamarId]
                                : 0;
                            $durasi = $hotelData['kamar_durasi'][$kamarId] ?? 1;
                            $catatan = $hotelData['kamar_catatan'][$kamarId] ?? null;

                            DeparturePaketTourHotel::create([
                                'id_departure' => $id,
                                'id_paket_tour' => $data['id_paket_tour'],
                                'id_hotel' => $hotelData['id_hotel'],
                                'id_kamar' => $kamarId,
                                'urutan' => $hotelData['urutan'] ?? 0,
                                'harga_per_malam' => $hargaPerMalam,
                                'durasi_menginap' => $durasi,
                                'jumlah_kamar' => $jumlahKamar,
                                'tipe_kamar' => $kamar->tipe_kamar,
                                'catatan' => $catatan,
                            ]);
                        }
                    } else {
                        // Fallback: single tipe kamar (backward compatibility)
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
            }

            $departure->recalculate();

            return $departure->load([
                'departurePaketTourHotels.hotel',
                'departurePaketTourHotels.kamar',
                'departurePaketTourHotels.paketTour'
            ]);
        });
    }

    public function getPaketTourHotelsByDeparture($id)
    {
        return DeparturePaketTourHotel::with(['hotel', 'kamar', 'paketTour'])
            ->where('id_departure', $id)
            ->orderBy('urutan')
            ->get();
    }
}
