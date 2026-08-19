<?php

namespace App\Services;

use App\Models\Perlengkapan;
use Illuminate\Support\Facades\DB;

class PerlengkapanService
{
    public function getAll(array $filters = [])
    {
        $query = Perlengkapan::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kode_perlengkapan', 'like', "%{$search}%")
                  ->orWhere('nama_perlengkapan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_perlengkapan', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return Perlengkapan::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Perlengkapan::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $perlengkapan = $this->getById($id);
            $perlengkapan->update($data);
            return $perlengkapan->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $perlengkapan = $this->getById($id);
            $nama = $perlengkapan->nama_perlengkapan;
            $perlengkapan->delete();
            return $nama;
        });
    }

    public function getKategoriOptions()
    {
        return [
            'Koper' => 'Koper',
            'Pakaian' => 'Pakaian',
            'Aksesoris' => 'Aksesoris',
            'Dokumen' => 'Dokumen',
            'Lainnya' => 'Lainnya',
        ];
    }

    public function getSatuanOptions()
    {
        return [
            'Pcs' => 'Pcs',
            'Set' => 'Set',
            'Pair' => 'Pasang',
            'Unit' => 'Unit',
            'Buah' => 'Buah',
            'Lembar' => 'Lembar',
        ];
    }

    public function getKategoriBadge($kategori)
    {
        $colors = [
            'Koper' => 'bg-blue-100 text-blue-700',
            'Pakaian' => 'bg-green-100 text-green-700',
            'Aksesoris' => 'bg-purple-100 text-purple-700',
            'Dokumen' => 'bg-yellow-100 text-yellow-700',
            'Lainnya' => 'bg-gray-100 text-gray-700',
        ];

        return $colors[$kategori] ?? 'bg-gray-100 text-gray-700';
    }

    public function getKategoriIcon($kategori)
    {
        $icons = [
            'Koper' => 'fa-suitcase',
            'Pakaian' => 'fa-tshirt',
            'Aksesoris' => 'fa-gem',
            'Dokumen' => 'fa-file-alt',
            'Lainnya' => 'fa-box',
        ];

        return $icons[$kategori] ?? 'fa-box';
    }
}
