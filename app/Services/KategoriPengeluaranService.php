<?php

namespace App\Services;

use App\Models\KategoriPengeluaran;
use Illuminate\Support\Facades\DB;

class KategoriPengeluaranService
{
    public function getAll(array $filters = [])
    {
        $query = KategoriPengeluaran::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_kategori', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return KategoriPengeluaran::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return KategoriPengeluaran::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $kategori = $this->getById($id);
            $kategori->update($data);
            return $kategori->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $kategori = $this->getById($id);
            $nama = $kategori->nama_kategori;
            $kategori->delete();
            return $nama;
        });
    }
}
