<?php

namespace App\Services;

use App\Models\Maskapai;
use Illuminate\Support\Facades\DB;

class MaskapaiService
{
    public function getAll(array $filters = [])
    {
        $query = Maskapai::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kode_maskapai', 'like', "%{$search}%")
                  ->orWhere('nama_maskapai', 'like', "%{$search}%")
                  ->orWhere('tipe_penerbangan', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_maskapai', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return Maskapai::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Maskapai::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $maskapai = $this->getById($id);
            $maskapai->update($data);
            return $maskapai->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $maskapai = $this->getById($id);
            $nama = $maskapai->nama_maskapai;
            $maskapai->delete();
            return $nama;
        });
    }

    public function getTipePenerbanganOptions()
    {
        return [
            'Domestik' => 'Domestik',
            'Internasional' => 'Internasional',
        ];
    }

    public function getBadgeTipe($tipe)
    {
        if ($tipe == 'Internasional') {
            return 'bg-blue-100 text-blue-700';
        }
        return 'bg-green-100 text-green-700';
    }
}
