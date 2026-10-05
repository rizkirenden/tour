<?php

namespace App\Services;

use App\Models\ProdukPaket;
use App\Models\PaketTour;
use App\Models\ProdukHargaBulanan;
use App\Models\ProdukKeberangkatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukPaketService
{
    public function getAll(array $filters = [])
    {
        $query = ProdukPaket::with('paketTour', 'hargaBulanan', 'keberangkatan');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['bulan_keberangkatan'])) {
            $query->whereHas('keberangkatan', function ($q) use ($filters) {
                $q->where('bulan', $filters['bulan_keberangkatan']);
            });
        }

        if (!empty($filters['tahun_keberangkatan'])) {
            $query->whereHas('keberangkatan', function ($q) use ($filters) {
                $q->where('tahun', $filters['tahun_keberangkatan']);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return ProdukPaket::with('paketTour', 'hargaBulanan', 'keberangkatan')->findOrFail($id);
    }

    public function getByIdWithRelations($id)
    {
        return ProdukPaket::with([
            'paketTour',
            'paketTour.hotels',
            'hargaBulanan',
            'keberangkatan',
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;

            if (!$data['include_tur']) {
                $data['paket_tour_id'] = null;
            }

            $data['durasi_tour'] = 0;
            if ($data['include_tur'] && !empty($data['paket_tour_id'])) {
                $paketTour = PaketTour::find($data['paket_tour_id']);
                if ($paketTour) {
                    $data['durasi_tour'] = (int) ($paketTour->durasi_hari ?? 0);
                }
            }

            $totalDurasi = 0;
            $totalDurasi += (int) ($data['durasi_perjalanan'] ?? 0);
            $totalDurasi += (int) ($data['durasi_mekkah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_madinah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_tour'] ?? 0);
            $data['durasi_hari'] = $totalDurasi;

            $data['total_harga'] = 0;

            $keberangkatanData = $data['keberangkatan'] ?? [];
            unset($data['keberangkatan']);

            $produk = ProdukPaket::create($data);

            if (!empty($data['harga_bulanan'])) {
                foreach ($data['harga_bulanan'] as $hargaData) {
                    if (!empty($hargaData['bulan']) && !empty($hargaData['tahun']) && isset($hargaData['harga'])) {
                        ProdukHargaBulanan::create([
                            'produk_paket_id' => $produk->id_produk,
                            'bulan' => $hargaData['bulan'],
                            'tahun' => $hargaData['tahun'],
                            'harga' => (int) $hargaData['harga'],
                            'flyer' => $hargaData['flyer'] ?? null,
                            'is_active' => $hargaData['is_active'] ?? true,
                        ]);
                    }
                }
            }

            foreach ($keberangkatanData as $k) {
                if (!empty($k['bulan']) && !empty($k['tahun'])) {
                    ProdukKeberangkatan::create([
                        'produk_paket_id' => $produk->id_produk,
                        'bulan' => $k['bulan'],
                        'tahun' => $k['tahun'],
                        'is_active' => $k['is_active'] ?? true,
                    ]);
                }
            }

            return $produk->fresh();
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $produk = $this->getById($id);

            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;

            if (!$data['include_tur']) {
                $data['paket_tour_id'] = null;
            }

            $data['durasi_tour'] = 0;
            if ($data['include_tur'] && !empty($data['paket_tour_id'])) {
                $paketTour = PaketTour::find($data['paket_tour_id']);
                if ($paketTour) {
                    $data['durasi_tour'] = (int) ($paketTour->durasi_hari ?? 0);
                }
            }

            $totalDurasi = 0;
            $totalDurasi += (int) ($data['durasi_perjalanan'] ?? 0);
            $totalDurasi += (int) ($data['durasi_mekkah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_madinah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_tour'] ?? 0);
            $data['durasi_hari'] = $totalDurasi;

            $keberangkatanData = $data['keberangkatan'] ?? [];
            unset($data['keberangkatan']);

            $produk->update($data);

            // ============ Harga bulanan ============
            if (isset($data['harga_bulanan'])) {
                $idsToKeep = [];
                foreach ($data['harga_bulanan'] as $hargaData) {
                    if (!empty($hargaData['id'])) {
                        $idsToKeep[] = $hargaData['id'];
                    }
                }

                if (!empty($idsToKeep)) {
                    $toDelete = $produk->hargaBulanan()->whereNotIn('id', $idsToKeep)->get();
                    foreach ($toDelete as $harga) {
                        if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
                            Storage::disk('public')->delete($harga->flyer);
                        }
                    }
                    $produk->hargaBulanan()->whereNotIn('id', $idsToKeep)->delete();
                } else {
                    foreach ($produk->hargaBulanan as $harga) {
                        if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
                            Storage::disk('public')->delete($harga->flyer);
                        }
                    }
                    $produk->hargaBulanan()->delete();
                }

                foreach ($data['harga_bulanan'] as $hargaData) {
                    if (!empty($hargaData['bulan']) && !empty($hargaData['tahun']) && isset($hargaData['harga'])) {
                        if (!empty($hargaData['id'])) {
                            ProdukHargaBulanan::where('id', $hargaData['id'])->update([
                                'bulan' => $hargaData['bulan'],
                                'tahun' => $hargaData['tahun'],
                                'harga' => (int) $hargaData['harga'],
                                'flyer' => $hargaData['flyer'] ?? null,
                                'is_active' => $hargaData['is_active'] ?? true,
                            ]);
                        } else {
                            ProdukHargaBulanan::create([
                                'produk_paket_id' => $produk->id_produk,
                                'bulan' => $hargaData['bulan'],
                                'tahun' => $hargaData['tahun'],
                                'harga' => (int) $hargaData['harga'],
                                'flyer' => $hargaData['flyer'] ?? null,
                                'is_active' => $hargaData['is_active'] ?? true,
                            ]);
                        }
                    }
                }
            }

            // ============ Keberangkatan (multiple) ============
            if (isset($data['keberangkatan'])) {
                $keepIds = [];
                foreach ($keberangkatanData as $k) {
                    if (!empty($k['id'])) {
                        $keepIds[] = $k['id'];
                    }
                }

                if (!empty($keepIds)) {
                    $produk->keberangkatan()->whereNotIn('id', $keepIds)->delete();
                } else {
                    $produk->keberangkatan()->delete();
                }

                foreach ($keberangkatanData as $k) {
                    if (!empty($k['bulan']) && !empty($k['tahun'])) {
                        if (!empty($k['id'])) {
                            ProdukKeberangkatan::where('id', $k['id'])->update([
                                'bulan' => $k['bulan'],
                                'tahun' => $k['tahun'],
                                'is_active' => $k['is_active'] ?? true,
                            ]);
                        } else {
                            ProdukKeberangkatan::create([
                                'produk_paket_id' => $produk->id_produk,
                                'bulan' => $k['bulan'],
                                'tahun' => $k['tahun'],
                                'is_active' => $k['is_active'] ?? true,
                            ]);
                        }
                    }
                }
            }

            return $produk->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $produk = $this->getById($id);
            $nama = $produk->nama_produk;

            foreach ($produk->hargaBulanan as $harga) {
                if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
                    Storage::disk('public')->delete($harga->flyer);
                }
            }

            $produk->hargaBulanan()->delete();
            $produk->keberangkatan()->delete();
            $produk->delete();

            return $nama;
        });
    }

    public function toggleStatus($id)
    {
        return DB::transaction(function () use ($id) {
            $produk = $this->getById($id);
            $produk->is_active = !$produk->is_active;
            $produk->save();

            return [
                'nama' => $produk->nama_produk,
                'status' => $produk->is_active ? 'diaktifkan' : 'dinonaktifkan',
                'is_active' => $produk->is_active
            ];
        });
    }

    public function getPaketTourInfo($id)
    {
        $paketTour = PaketTour::with('hotels')->find($id);
        if (!$paketTour) {
            return null;
        }

        return [
            'durasi_hari' => $paketTour->durasi_hari ?? 0,
            'kota_tujuan' => $paketTour->kota_tujuan,
            'negara' => $paketTour->negara,
            'deskripsi' => $paketTour->deskripsi,
            'total_harga_hotel' => $paketTour->total_harga_hotel_formatted ?? 'Rp 0',
        ];
    }
}
