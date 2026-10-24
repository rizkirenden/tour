<?php

namespace App\Services;

use App\Models\Departure;
use App\Models\PengeluaranLainLain;
use Illuminate\Support\Facades\DB;

class RekapKeuanganService
{
    /**
     * Ambil total keuntungan (profit) dari semua departure
     * sesuai filter periode (berdasarkan tanggal_keberangkatan).
     */
    public function getRingkasanDeparture(array $filters = [])
    {
        $query = Departure::query();

        if (!empty($filters['dari_tanggal'])) {
            $query->whereDate('tanggal_keberangkatan', '>=', $filters['dari_tanggal']);
        }
        if (!empty($filters['sampai_tanggal'])) {
            $query->whereDate('tanggal_keberangkatan', '<=', $filters['sampai_tanggal']);
        }
        if (!empty($filters['id_produk'])) {
            $query->where('id_produk', $filters['id_produk']);
        }
        if (isset($filters['status_selesai']) && $filters['status_selesai'] !== '') {
            $query->where('perjalanan_selesai', (bool) $filters['status_selesai']);
        }

        $departures = $query->orderBy('tanggal_keberangkatan', 'desc')->get();

        $totalKeuntungan = (int) $departures->sum('keuntungan');

        $profitCount = $departures->where('keuntungan', '>', 0)->count();
        $lossCount   = $departures->where('keuntungan', '<', 0)->count();
        $jamaahCount = (int) $departures->sum('jamaah_terdaftar');

        return [
            'rows'              => $departures,
            'total_keuntungan'  => $totalKeuntungan,
            'departure_count'   => $departures->count(),
            'profit_count'      => $profitCount,
            'loss_count'        => $lossCount,
            'jamaah_count'      => $jamaahCount,
        ];
    }

    /**
     * Ambil semua pengeluaran lain-lain manual sesuai filter.
     * ⚠️ TIDAK memakai relasi `user` karena tabel users belum ada.
     */
    public function getPengeluaranManual(array $filters = [])
    {
        $query = PengeluaranLainLain::query();

        if (!empty($filters['dari_tanggal'])) {
            $query->whereDate('tanggal', '>=', $filters['dari_tanggal']);
        }
        if (!empty($filters['sampai_tanggal'])) {
            $query->whereDate('tanggal', '<=', $filters['sampai_tanggal']);
        }
        if (!empty($filters['kategori_pengeluaran'])) {
            $query->where('kategori', $filters['kategori_pengeluaran']);
        }
        if (!empty($filters['search_pengeluaran'])) {
            $search = $filters['search_pengeluaran'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengeluaran', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('tanggal', 'desc')
            ->orderBy('id_pengeluaran_lain', 'desc')
            ->get();

        return [
            'items' => $items,
            'total' => (int) $items->sum('nominal'),
        ];
    }

    /**
     * Rekap lengkap: departure (profit) + pengeluaran manual (biaya).
     */
    public function getRekapLengkap(array $filters = [])
    {
        $departure = $this->getRingkasanDeparture($filters);
        $pengeluaran = $this->getPengeluaranManual($filters);

        $totalBersih = $departure['total_keuntungan'] - $pengeluaran['total'];

        return [
            'departure'    => $departure,
            'pengeluaran'  => $pengeluaran,
            'total_bersih' => $totalBersih,
        ];
    }

    /**
     * Kelompok pengeluaran per kategori (untuk ringkasan chart).
     */
    public function getPengeluaranPerKategori(array $filters = [])
    {
        $query = PengeluaranLainLain::query();

        if (!empty($filters['dari_tanggal'])) {
            $query->whereDate('tanggal', '>=', $filters['dari_tanggal']);
        }
        if (!empty($filters['sampai_tanggal'])) {
            $query->whereDate('tanggal', '<=', $filters['sampai_tanggal']);
        }

        return $query->select('kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();
    }

    public function getKategoriOptions()
    {
        return PengeluaranLainLain::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');
    }

    public function addPengeluaran(array $data)
    {
        return DB::transaction(function () use ($data) {
            return PengeluaranLainLain::create([
                'tanggal'          => $data['tanggal'],
                'nama_pengeluaran' => $data['nama_pengeluaran'],
                'kategori'         => $data['kategori'] ?? null,
                'nominal'          => (int) $data['nominal'],
                'keterangan'       => $data['keterangan'] ?? null,
                'id_user'          => $data['id_user'] ?? null,
            ]);
        });
    }

    public function updatePengeluaran($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $item = PengeluaranLainLain::findOrFail($id);
            $item->update([
                'tanggal'          => $data['tanggal'] ?? $item->tanggal,
                'nama_pengeluaran' => $data['nama_pengeluaran'] ?? $item->nama_pengeluaran,
                'kategori'         => $data['kategori'] ?? $item->kategori,
                'nominal'          => isset($data['nominal']) ? (int) $data['nominal'] : $item->nominal,
                'keterangan'       => $data['keterangan'] ?? $item->keterangan,
            ]);
            return $item->fresh();
        });
    }

    public function deletePengeluaran($id)
    {
        return DB::transaction(function () use ($id) {
            return PengeluaranLainLain::findOrFail($id)->delete();
        });
    }
}