<?php

namespace App\Services;

use App\Models\KotaAsal;
use Illuminate\Support\Facades\DB;

class KotaAsalService
{
    public function getAll(array $filters = [])
    {
        $query = KotaAsal::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_kota', 'like', "%{$search}%")
                  ->orWhere('provinsi', 'like', "%{$search}%")
                  ->orWhere('pulau', 'like', "%{$search}%")
                  ->orWhere('bandara_terdekat', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_kota', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return KotaAsal::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return KotaAsal::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $kota = $this->getById($id);
            $kota->update($data);
            return $kota->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $kota = $this->getById($id);
            $nama = $kota->nama_kota;
            $kota->delete();
            return $nama;
        });
    }
}
