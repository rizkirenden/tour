<?php

namespace App\Services;

use App\Models\ProdukPaket;
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
            // Set default values
            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;
            $data['paket_tour_id'] = $data['paket_tour_id'] ?? null;
            $data['durasi_mekkah'] = $data['durasi_mekkah'] ?? 4;
            $data['durasi_madinah'] = $data['durasi_madinah'] ?? 4;
            $data['harga_dasar'] = (int) ($data['harga_dasar'] ?? 0);

            // Jika include_tur = false, set paket_tour_id menjadi null
            if (!$data['include_tur']) {
                $data['paket_tour_id'] = null;
            }

            // Hitung total durasi
            $totalDurasi = 0;
            if ($data['durasi_perjalanan']) {
                $totalDurasi += $data['durasi_perjalanan'];
            }
            $totalDurasi += $data['durasi_mekkah'] ?? 0;
            $totalDurasi += $data['durasi_madinah'] ?? 0;
            $data['durasi_hari'] = (int) $totalDurasi;

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

            // Hitung total durasi
            $totalDurasi = 0;
            if ($data['durasi_perjalanan']) {
                $totalDurasi += $data['durasi_perjalanan'];
            }
            $totalDurasi += $data['durasi_mekkah'] ?? 0;
            $totalDurasi += $data['durasi_madinah'] ?? 0;
            $data['durasi_hari'] = (int) $totalDurasi;

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
}
