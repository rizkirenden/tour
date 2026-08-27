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
            return Diskon::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $diskon = $this->getById($id);
            $data['sudah_digunakan'] = $data['sudah_digunakan'] ?? 0;
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
        $produk = ProdukPaket::all();
        $options = [
            '' => 'Semua Produk',
        ];

        foreach ($produk as $p) {
            $options[$p->id_produk] = $p->nama_produk;
        }

        return $options;
    }

    public function getAvailableDiskon($produkId = null)
    {
        $query = Diskon::where(function($q) {
            $q->whereNull('kuota')
              ->orWhereRaw('kuota > sudah_digunakan');
        });

        if ($produkId) {
            $query->where(function($q) use ($produkId) {
                $q->where('berlaku_untuk_produk', $produkId)
                  ->orWhereNull('berlaku_untuk_produk')
                  ->orWhere('berlaku_untuk_produk', '');
            });
        }

        return $query->orderBy('nilai_diskon', 'desc')->get();
    }
}
