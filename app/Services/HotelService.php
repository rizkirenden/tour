<?php

namespace App\Services;

use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class HotelService
{
    public function getAll(array $filters = [])
    {
        $query = Hotel::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kode_hotel', 'like', "%{$search}%")
                  ->orWhere('nama_hotel', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%")
                  ->orWhere('negara', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_hotel', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return Hotel::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Hotel::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $hotel = $this->getById($id);
            $hotel->update($data);
            return $hotel->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $hotel = $this->getById($id);
            $nama = $hotel->nama_hotel;
            $hotel->delete();
            return $nama;
        });
    }
}
