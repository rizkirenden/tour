<?php

namespace App\Services;

use App\Models\Maskapai;
use App\Models\MaskapaiTipePenerbangan;
use Illuminate\Support\Facades\DB;

class MaskapaiService
{
    public function getAll(array $filters = [])
    {
        $query = Maskapai::with('tipePenerbangan');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_maskapai', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('nama_maskapai', 'asc')->paginate(10);
    }

    public function getById($id)
    {
        return Maskapai::with('tipePenerbangan')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Ambil tipe penerbangan
            $tipeList = $data['tipe_penerbangan'] ?? [];
            unset($data['tipe_penerbangan']);

            // Buat maskapai
            $maskapai = Maskapai::create($data);

            // Tambah tipe penerbangan
            if (!empty($tipeList)) {
                foreach ($tipeList as $tipe) {
                    MaskapaiTipePenerbangan::create([
                        'id_maskapai' => $maskapai->id_maskapai,
                        'tipe_penerbangan' => $tipe
                    ]);
                }
            }

            return $maskapai->load('tipePenerbangan');
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $maskapai = $this->getById($id);

            // Ambil tipe penerbangan
            $tipeList = $data['tipe_penerbangan'] ?? [];
            unset($data['tipe_penerbangan']);

            // Update maskapai
            $maskapai->update($data);

            // Hapus tipe lama
            MaskapaiTipePenerbangan::where('id_maskapai', $id)->delete();

            // Tambah tipe baru
            if (!empty($tipeList)) {
                foreach ($tipeList as $tipe) {
                    MaskapaiTipePenerbangan::create([
                        'id_maskapai' => $id,
                        'tipe_penerbangan' => $tipe
                    ]);
                }
            }

            return $maskapai->load('tipePenerbangan');
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $maskapai = $this->getById($id);
            $nama = $maskapai->nama_maskapai;

            // Hapus tipe penerbangan
            MaskapaiTipePenerbangan::where('id_maskapai', $id)->delete();

            // Hapus maskapai
            $maskapai->delete();

            return $nama;
        });
    }

    public function getTipeOptions()
    {
        return [
            'Domestik' => 'Domestik',
            'Internasional' => 'Internasional',
        ];
    }
}
