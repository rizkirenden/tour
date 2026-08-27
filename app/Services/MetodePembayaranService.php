<?php

namespace App\Services;

use App\Models\MetodePembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MetodePembayaranService
{
    public function getAll(array $filters = [])
    {
        $query = MetodePembayaran::query();

        if (!empty($filters['jenis_pembayaran'])) {
            $query->where('jenis_pembayaran', $filters['jenis_pembayaran']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('kode_bank', 'like', "%{$search}%")
                  ->orWhere('nama_bank', 'like', "%{$search}%")
                  ->orWhere('nomor_rekening', 'like', "%{$search}%")
                  ->orWhere('atas_nama', 'like', "%{$search}%")
                  ->orWhere('e_wallet_type', 'like', "%{$search}%")
                  ->orWhere('nomor_telepon', 'like', "%{$search}%")
                  ->orWhere('jenis_pembayaran', 'like', "%{$search}%");
            });
        }

        return $query->orderByRaw("FIELD(jenis_pembayaran, 'cash', 'bank_transfer', 'e_wallet')")
                    ->orderBy('nama_bank', 'asc')
                    ->paginate(10);
    }

    public function getById($id)
    {
        return MetodePembayaran::findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $this->validatePaymentData($data);

            $data['is_active'] = $data['is_active'] ?? true;

            if ($data['jenis_pembayaran'] === MetodePembayaran::JENIS_CASH) {
                $data['kode_bank'] = 'CASH';
                $data['nama_bank'] = 'Cash / Tunai';
                $data['nomor_rekening'] = null;
                $data['atas_nama'] = null;
            }

            return MetodePembayaran::create($data);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $metode = $this->getById($id);

            if ($metode->jenis_pembayaran === MetodePembayaran::JENIS_CASH &&
                isset($data['jenis_pembayaran']) &&
                $data['jenis_pembayaran'] !== MetodePembayaran::JENIS_CASH) {
                throw ValidationException::withMessages([
                    'jenis_pembayaran' => 'Tidak dapat mengubah jenis pembayaran cash'
                ]);
            }

            $this->validatePaymentData($data, $metode);

            $data['is_active'] = $data['is_active'] ?? true;

            if ($data['jenis_pembayaran'] === MetodePembayaran::JENIS_CASH) {
                $data['kode_bank'] = 'CASH';
                $data['nama_bank'] = 'Cash / Tunai';
                $data['nomor_rekening'] = null;
                $data['atas_nama'] = null;
            }

            $metode->update($data);
            return $metode->fresh();
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $metode = $this->getById($id);
            $nama = $metode->display_name;

            if ($metode->transaksis()->exists()) {
                throw ValidationException::withMessages([
                    'id' => 'Metode pembayaran ini sudah digunakan dalam transaksi dan tidak dapat dihapus.'
                ]);
            }

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
                'nama' => $metode->display_name,
                'status' => $metode->is_active ? 'diaktifkan' : 'dinonaktifkan',
                'is_active' => $metode->is_active
            ];
        });
    }

    private function validatePaymentData(array $data, $existing = null)
    {
        $jenis = $data['jenis_pembayaran'] ?? null;

        if ($jenis === MetodePembayaran::JENIS_CASH) {
            return;
        }

        if ($jenis === MetodePembayaran::JENIS_E_WALLET) {
            if (empty($data['e_wallet_type'])) {
                throw ValidationException::withMessages([
                    'e_wallet_type' => 'Jenis E-Wallet harus diisi'
                ]);
            }
            if (empty($data['nomor_telepon'])) {
                throw ValidationException::withMessages([
                    'nomor_telepon' => 'Nomor telepon harus diisi'
                ]);
            }
            return;
        }

        if ($jenis === MetodePembayaran::JENIS_BANK_TRANSFER) {
            if (empty($data['kode_bank'])) {
                throw ValidationException::withMessages([
                    'kode_bank' => 'Kode bank harus diisi'
                ]);
            }
            if (empty($data['nama_bank'])) {
                throw ValidationException::withMessages([
                    'nama_bank' => 'Nama bank harus diisi'
                ]);
            }
            if (empty($data['nomor_rekening'])) {
                throw ValidationException::withMessages([
                    'nomor_rekening' => 'Nomor rekening harus diisi'
                ]);
            }
            if (empty($data['atas_nama'])) {
                throw ValidationException::withMessages([
                    'atas_nama' => 'Atas nama harus diisi'
                ]);
            }
        }
    }

    public function getActivePaymentMethods()
    {
        return MetodePembayaran::active()->orderBy('jenis_pembayaran')->get();
    }

    public function getPaymentMethodsByType($type)
    {
        return MetodePembayaran::where('jenis_pembayaran', $type)->active()->get();
    }
}
