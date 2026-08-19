<?php

namespace App\Services;

use App\Models\ProdukPaket;
use Illuminate\Support\Facades\DB;

class ProdukPaketService
{
    public function getAll(array $filters = [])
    {
        $query = ProdukPaket::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('kode_produk', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return ProdukPaket::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['kode_produk'])) {
                $data['kode_produk'] = $this->generateKodeProduk();
            }

            $data['multiple_hotel_enabled'] = $data['multiple_hotel_enabled'] ?? false;
            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;
            $data['kapasitas_kamar_default'] = $data['kapasitas_kamar_default'] ?? 4;
            $data['durasi_mekkah'] = $data['durasi_mekkah'] ?? 4;
            $data['durasi_madinah'] = $data['durasi_madinah'] ?? 4;
            $data['durasi_transit'] = $data['durasi_transit'] ?? 1;
            $data['harga_visa'] = $data['harga_visa'] ?? 0;
            $data['harga_handling'] = $data['harga_handling'] ?? 0;
            $data['harga_muthowwif'] = $data['harga_muthowwif'] ?? 0;

            return ProdukPaket::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $produk = $this->getById($id);
            $data['multiple_hotel_enabled'] = $data['multiple_hotel_enabled'] ?? false;
            $data['include_tur'] = $data['include_tur'] ?? false;
            $data['is_active'] = $data['is_active'] ?? true;
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

    /**
     * Toggle status produk
     */
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

    private function generateKodeProduk()
    {
        $prefix = 'PKT';
        $year = date('Y');
        $last = ProdukPaket::where('kode_produk', 'like', "{$prefix}-%")->count();
        $number = str_pad($last + 1, 3, '0', STR_PAD_LEFT);
        return "{$prefix}-{$number}-{$year}";
    }
}
