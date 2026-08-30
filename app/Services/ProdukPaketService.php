<?php

namespace App\Services;

use App\Models\ProdukPaket;
use App\Models\PaketTour;
use App\Models\ProdukHargaBulanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdukPaketService
{
    public function getAll(array $filters = [])
    {
        $query = ProdukPaket::with('paketTour', 'hargaBulanan');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return ProdukPaket::with('paketTour', 'hargaBulanan')->findOrFail($id);
    }

    public function getByIdWithRelations($id)
    {
        return ProdukPaket::with([
            'paketTour',
            'paketTour.hotels',
            'hargaBulanan',
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

            $produk = ProdukPaket::create($data);

            if (!empty($data['harga_bulanan'])) {
                foreach ($data['harga_bulanan'] as $hargaData) {
                    if (!empty($hargaData['bulan']) && !empty($hargaData['tahun']) && isset($hargaData['harga'])) {
                        $harga = ProdukHargaBulanan::create([
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

            $produk->update($data);

            if (isset($data['harga_bulanan'])) {
                $idsToKeep = [];
                foreach ($data['harga_bulanan'] as $hargaData) {
                    if (!empty($hargaData['id'])) {
                        $idsToKeep[] = $hargaData['id'];
                    }
                }

                if (!empty($idsToKeep)) {
                    // Hapus flyer dari harga yang akan dihapus
                    $toDelete = $produk->hargaBulanan()->whereNotIn('id', $idsToKeep)->get();
                    foreach ($toDelete as $harga) {
                        if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
                            Storage::disk('public')->delete($harga->flyer);
                        }
                    }
                    $produk->hargaBulanan()->whereNotIn('id', $idsToKeep)->delete();
                } else {
                    // Hapus semua flyer
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
                            // Update existing - flyer sudah ditangani di controller
                            ProdukHargaBulanan::where('id', $hargaData['id'])->update([
                                'bulan' => $hargaData['bulan'],
                                'tahun' => $hargaData['tahun'],
                                'harga' => (int) $hargaData['harga'],
                                'flyer' => $hargaData['flyer'] ?? null,
                                'is_active' => $hargaData['is_active'] ?? true,
                            ]);
                        } else {
                            // Create new
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

            return $produk->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $produk = $this->getById($id);
            $nama = $produk->nama_produk;

            // Hapus flyer dari semua harga bulanan
            foreach ($produk->hargaBulanan as $harga) {
                if ($harga->flyer && Storage::disk('public')->exists($harga->flyer)) {
                    Storage::disk('public')->delete($harga->flyer);
                }
            }

            $produk->hargaBulanan()->delete();
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
