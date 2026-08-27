<?php

namespace App\Services;

use App\Models\ProdukPaket;
use App\Models\PaketTour;
use Illuminate\Support\Facades\DB;

class ProdukPaketService
{
    public function getAll(array $filters = [])
    {
        $query = ProdukPaket::with('paketTour');

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
        return ProdukPaket::with('paketTour')->findOrFail($id);
    }

    public function getByIdWithRelations($id)
    {
        return ProdukPaket::with([
            'paketTour',
            'paketTour.hotels',
        ])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;
            $data['harga_dasar'] = (int) ($data['harga_dasar'] ?? 0);

            // Jika include_tur = false, set paket_tour_id menjadi null
            if (!$data['include_tur']) {
                $data['paket_tour_id'] = null;
            }

            // Ambil durasi_tour dari paket_tour yang dipilih
            $data['durasi_tour'] = 0;
            if ($data['include_tur'] && !empty($data['paket_tour_id'])) {
                $paketTour = PaketTour::find($data['paket_tour_id']);
                if ($paketTour) {
                    $data['durasi_tour'] = (int) ($paketTour->durasi_hari ?? 0);
                }
            }

            // Hitung total durasi dari semua komponen
            $totalDurasi = 0;
            $totalDurasi += (int) ($data['durasi_perjalanan'] ?? 0);
            $totalDurasi += (int) ($data['durasi_mekkah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_madinah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_tour'] ?? 0);
            $data['durasi_hari'] = $totalDurasi;

            // Total harga hanya dari harga_dasar
            $data['total_harga'] = $data['harga_dasar'];

            // Create produk
            $produk = ProdukPaket::create($data);

            return $produk->fresh();
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $produk = $this->getById($id);

            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;
            $data['harga_dasar'] = (int) ($data['harga_dasar'] ?? 0);

            // Jika include_tur = false, set paket_tour_id menjadi null
            if (!$data['include_tur']) {
                $data['paket_tour_id'] = null;
            }

            // Ambil durasi_tour dari paket_tour yang dipilih
            $data['durasi_tour'] = 0;
            if ($data['include_tur'] && !empty($data['paket_tour_id'])) {
                $paketTour = PaketTour::find($data['paket_tour_id']);
                if ($paketTour) {
                    $data['durasi_tour'] = (int) ($paketTour->durasi_hari ?? 0);
                }
            }

            // Hitung total durasi dari semua komponen
            $totalDurasi = 0;
            $totalDurasi += (int) ($data['durasi_perjalanan'] ?? 0);
            $totalDurasi += (int) ($data['durasi_mekkah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_madinah'] ?? 0);
            $totalDurasi += (int) ($data['durasi_tour'] ?? 0);
            $data['durasi_hari'] = $totalDurasi;

            // Total harga hanya dari harga_dasar
            $data['total_harga'] = $data['harga_dasar'];

            // Update produk
            $produk->update($data);

            return $produk->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $produk = $this->getById($id);
            $nama = $produk->nama_produk;
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
