<?php

namespace App\Services;

use App\Models\JenisTransaksi;
use Illuminate\Support\Facades\DB;

class JenisTransaksiService
{
    public function getAll(array $filters = [])
    {
        $query = JenisTransaksi::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('kode', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return JenisTransaksi::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return JenisTransaksi::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $jenis = $this->getById($id);
            $jenis->update($data);
            return $jenis->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $jenis = $this->getById($id);
            $nama = $jenis->nama;
            $jenis->delete();
            return $nama;
        });
    }
}
