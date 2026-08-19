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

    public function getWarnaOptions()
    {
        return [
            'blue' => 'Biru',
            'yellow' => 'Kuning',
            'green' => 'Hijau',
            'red' => 'Merah',
            'purple' => 'Ungu',
            'gray' => 'Abu-abu',
            'orange' => 'Oranye',
            'pink' => 'Merah Muda',
            'indigo' => 'Indigo',
            'teal' => 'Teal',
        ];
    }

    public function getBadgeColor($warna)
    {
        $colors = [
            'blue' => 'bg-blue-100 text-blue-700',
            'yellow' => 'bg-yellow-100 text-yellow-700',
            'green' => 'bg-green-100 text-green-700',
            'red' => 'bg-red-100 text-red-700',
            'purple' => 'bg-purple-100 text-purple-700',
            'gray' => 'bg-gray-100 text-gray-700',
            'orange' => 'bg-orange-100 text-orange-700',
            'pink' => 'bg-pink-100 text-pink-700',
            'indigo' => 'bg-indigo-100 text-indigo-700',
            'teal' => 'bg-teal-100 text-teal-700',
        ];

        return $colors[$warna] ?? 'bg-gray-100 text-gray-700';
    }
}
