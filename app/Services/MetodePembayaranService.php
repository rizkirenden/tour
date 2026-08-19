<?php

namespace App\Services;

use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\DB;

class MetodePembayaranService
{
    public function getAll(array $filters = [])
    {
        $query = MetodePembayaran::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kode_bank', 'like', "%{$search}%")
                  ->orWhere('nama_bank', 'like', "%{$search}%")
                  ->orWhere('nomor_rekening', 'like', "%{$search}%")
                  ->orWhere('atas_nama', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_bank', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return MetodePembayaran::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['is_active'] = $data['is_active'] ?? true;
            return MetodePembayaran::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $metode = $this->getById($id);
            $data['is_active'] = $data['is_active'] ?? true;
            $metode->update($data);
            return $metode->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $metode = $this->getById($id);
            $nama = $metode->nama_bank;
            $metode->delete();
            return $nama;
        });
    }

    public function toggleStatus($id)
    {
        return DB::transaction(function () use ($id) {
            $metode = $this->getById($id);
            $metode->is_active = !$metode->is_active;
            $metode->save();

            return [
                'nama' => $metode->nama_bank,
                'status' => $metode->is_active ? 'diaktifkan' : 'dinonaktifkan',
                'is_active' => $metode->is_active
            ];
        });
    }
}
