<?php

namespace App\Services;

use App\Models\Diskon;
use App\Models\ProdukPaket;
use Illuminate\Support\Facades\DB;

class DiskonService
{
    public function getAll(array $filters = [])
    {
        $query = Diskon::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_diskon', 'like', "%{$search}%")
                  ->orWhere('berlaku_untuk_produk', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getById($id)
    {
        return Diskon::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['sudah_digunakan'] = $data['sudah_digunakan'] ?? 0;
            $data['nilai_diskon'] = (int) ($data['nilai_diskon'] ?? 0);
            return Diskon::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $diskon = $this->getById($id);
            $data['sudah_digunakan'] = $data['sudah_digunakan'] ?? 0;
            $data['nilai_diskon'] = (int) ($data['nilai_diskon'] ?? 0);
            $diskon->update($data);
            return $diskon->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $diskon = $this->getById($id);
            $nama = $diskon->nama_diskon;
            $diskon->delete();
            return $nama;
        });
    }

    public function getProdukOptions()
    {
        $produk = ProdukPaket::where('is_active', true)
            ->orderBy('nama_produk', 'asc')
            ->pluck('nama_produk', 'nama_produk')
            ->toArray();

        $options = ['Semua Produk' => 'Semua Produk'];
        foreach ($produk as $key => $value) {
            $options[$value] = $value;
        }

        return $options;
    }
}
