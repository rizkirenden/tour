<?php

namespace App\Services;

use App\Models\StatusKeberangkatan;
use Illuminate\Support\Facades\DB;

class StatusKeberangkatanService
{
    public function getAll(array $filters = [])
    {
        $query = StatusKeberangkatan::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_status', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_status', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return StatusKeberangkatan::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return StatusKeberangkatan::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $status = $this->getById($id);
            $status->update($data);
            return $status->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $status = $this->getById($id);
            $nama = $status->nama_status;
            $status->delete();
            return $nama;
        });
    }
}
