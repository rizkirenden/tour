<?php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\KeluargaService;
use App\Models\TransaksiPembayaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KeluargaController extends Controller
{
    protected $service;

    public function __construct(KeluargaService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status_pembayaran']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('keluargas.table', compact('data'));
        }

        return view('keluargas.index', compact('data'));
    }

    public function create()
    {
        $produkPakets = $this->service->getProdukPakets();
        $kotaAsals = $this->service->getKotaAsals();
        $hubunganOptions = $this->service->getHubunganOptions();
        $diskons = $this->service->getDiskons();
        return view('keluargas.create', compact('produkPakets', 'kotaAsals', 'hubunganOptions', 'diskons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_keluarga' => 'required|string|max:100',
            'produk_paket' => 'required|string|max:100|exists:produk_pakets,nama_produk',
            'id_diskon' => 'nullable|exists:diskons,id_diskon',
            'agent' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',
            'bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
            'jamaahs' => 'required|array|min:1',
            'jamaahs.*.nama_lengkap' => 'required|string|max:100',
            'jamaahs.*.hubungan_keluarga' => 'nullable|string|max:30',
            'jamaahs.*.is_kepala_keluarga' => 'nullable|boolean',
            'jamaahs.*.jenis_kelamin' => 'nullable|in:L,P',
            'jamaahs.*.telepon' => 'nullable|string|max:20',
            'jamaahs.*.alamat' => 'nullable|string',
            'jamaahs.*.kota_asal' => 'nullable|string|max:50|exists:kota_asals,nama_kota',
            'jamaahs.*.pulau' => 'nullable|string|max:20',
            'jamaahs.*.bandara_keberangkatan' => 'nullable|string|max:50',
            'jamaahs.*.nomor_paspor' => 'nullable|string|max:20',
            'jamaahs.*.tanggal_lahir' => 'nullable|date',
            'jamaahs.*.tempat_lahir' => 'nullable|string|max:100',
            'jamaahs.*.file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            // Pendampingan per jamaah
            'jamaahs.*.pendampingan_nama' => 'nullable|string|max:100',
            'jamaahs.*.pendampingan_fee' => 'nullable|integer|min:0',
            'jamaahs.*.pendampingan_fee_petugas' => 'nullable|integer|min:0',
        ]);

        $keluarga = $this->service->create($validated);

        return redirect()->route('transaksional.keluarga.index')
            ->with('success', "Keluarga '{$keluarga->nama_keluarga}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $keluarga = $this->service->getById($id);

        // Ambil semua transaksi pembayaran dari semua jamaah dalam keluarga
        $jamaahIds = $keluarga->jamaahs->pluck('id_jamaah')->toArray();
        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisTransaksi', 'jamaah'])
            ->whereIn('id_jamaah', $jamaahIds)
            ->orderBy('created_at', 'asc')  // ASC untuk urutan dari yang lama ke baru
            ->get();

        // Rekap pembayaran per jamaah
        $rekapJamaah = [];
        foreach ($keluarga->jamaahs as $jamaah) {
            $rekapJamaah[$jamaah->id_jamaah] = [
                'nama' => $jamaah->nama_lengkap,
                'total_tagihan' => $jamaah->total_tagihan_setelah_diskon,
                'total_dibayar' => $jamaah->total_dibayar,
                'sisa_tagihan' => $jamaah->sisa_tagihan,
                'status' => $jamaah->status_pembayaran,
                'transaksi_count' => TransaksiPembayaran::where('id_jamaah', $jamaah->id_jamaah)->count()
            ];
        }

        return view('keluargas.show', compact('keluarga', 'transaksis', 'rekapJamaah'));
    }

    public function edit($id)
    {
        $keluarga = $this->service->getById($id);
        $produkPakets = $this->service->getProdukPakets();
        $kotaAsals = $this->service->getKotaAsals();
        $hubunganOptions = $this->service->getHubunganOptions();
        $diskons = $this->service->getDiskons();
        return view('keluargas.edit', compact('keluarga', 'produkPakets', 'kotaAsals', 'hubunganOptions', 'diskons'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_keluarga' => 'required|string|max:100',
            'produk_paket' => 'required|string|max:100|exists:produk_pakets,nama_produk',
            'id_diskon' => 'nullable|exists:diskons,id_diskon',
            'agent' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',
            'bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
            'jamaahs' => 'required|array|min:1',
            'jamaahs.*.id_jamaah' => 'nullable|exists:jamaahs,id_jamaah',
            'jamaahs.*.nama_lengkap' => 'required|string|max:100',
            'jamaahs.*.hubungan_keluarga' => 'nullable|string|max:30',
            'jamaahs.*.is_kepala_keluarga' => 'nullable|boolean',
            'jamaahs.*.jenis_kelamin' => 'nullable|in:L,P',
            'jamaahs.*.telepon' => 'nullable|string|max:20',
            'jamaahs.*.alamat' => 'nullable|string',
            'jamaahs.*.kota_asal' => 'nullable|string|max:50|exists:kota_asals,nama_kota',
            'jamaahs.*.pulau' => 'nullable|string|max:20',
            'jamaahs.*.bandara_keberangkatan' => 'nullable|string|max:50',
            'jamaahs.*.nomor_paspor' => 'nullable|string|max:20',
            'jamaahs.*.tanggal_lahir' => 'nullable|date',
            'jamaahs.*.tempat_lahir' => 'nullable|string|max:100',
            'jamaahs.*.file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            // Pendampingan per jamaah
            'jamaahs.*.pendampingan_nama' => 'nullable|string|max:100',
            'jamaahs.*.pendampingan_fee' => 'nullable|integer|min:0',
            'jamaahs.*.pendampingan_fee_petugas' => 'nullable|integer|min:0',
        ]);

        $keluarga = $this->service->update($id, $validated);

        return redirect()->route('transaksional.keluarga.index')
            ->with('success', "Keluarga '{$keluarga->nama_keluarga}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $nama = $this->service->delete($id);

        return redirect()->route('transaksional.keluarga.index')
            ->with('success', "Keluarga '{$nama}' berhasil dihapus!");
    }

    public function pembayaran($id)
    {
        $keluarga = $this->service->getById($id);
        $metodePembayarans = \App\Models\MetodePembayaran::active()->get();
        $jenisTransaksis = \App\Models\JenisTransaksi::all();

        return view('keluargas.pembayaran', compact('keluarga', 'metodePembayarans', 'jenisTransaksis'));
    }

    public function bayar(Request $request, $id)
    {
        $validated = $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayarans,id_metode',
            'id_jenis_transaksi' => 'required|exists:jenis_transaksis,id_jenis',
            'tanggal_transaksi' => 'required|date',
            'jumlah_bayar' => 'required|integer|min:1',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $keluarga = $this->service->getById($id);
        $totalTagihan = $keluarga->total_tagihan_setelah_diskon;
        $totalDibayarSaatIni = $keluarga->total_dibayar;
        $jumlahBayar = $validated['jumlah_bayar'];
        $jenisTransaksi = \App\Models\JenisTransaksi::find($validated['id_jenis_transaksi']);

        if ($jenisTransaksi->kode == 'LUNAS' && $jumlahBayar < ($totalTagihan - $totalDibayarSaatIni)) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar untuk pelunasan harus sebesar sisa tagihan: ' . number_format($totalTagihan - $totalDibayarSaatIni, 0, ',', '.'));
        }

        if ($jumlahBayar > ($totalTagihan - $totalDibayarSaatIni)) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar melebihi sisa tagihan!');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_bukti_keluarga_' . $keluarga->id_keluarga . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti-pembayaran', $filename, 'public');
        }

        $totalDibayarBaru = $totalDibayarSaatIni + $jumlahBayar;
        $sisaTagihanBaru = $totalTagihan - $totalDibayarBaru;

        if ($sisaTagihanBaru <= 0) {
            $status = 'Lunas';
        } elseif ($jenisTransaksi->kode == 'DP') {
            $status = 'DP';
        } else {
            $status = 'Setoran';
        }

        $keluarga->update([
            'total_dibayar' => $totalDibayarBaru,
            'sisa_tagihan' => $sisaTagihanBaru,
            'status_pembayaran' => $status
        ]);

        $jamaahs = $keluarga->jamaahs;
        $totalTagihanKeluarga = $keluarga->total_tagihan_setelah_diskon;

        foreach ($jamaahs as $jamaah) {
            $proporsi = $jamaah->total_tagihan_setelah_diskon / $totalTagihanKeluarga;
            $bayarPerJamaah = $jumlahBayar * $proporsi;

            $totalDibayarJamaah = $jamaah->total_dibayar + $bayarPerJamaah;
            $sisaTagihanJamaah = $jamaah->total_tagihan_setelah_diskon - $totalDibayarJamaah;

            if ($sisaTagihanJamaah <= 0) {
                $statusJamaah = 'Lunas';
            } elseif ($jamaah->total_dibayar == 0) {
                $statusJamaah = 'DP';
            } else {
                $statusJamaah = 'Setoran';
            }

            $jamaah->update([
                'total_dibayar' => $totalDibayarJamaah,
                'sisa_tagihan' => $sisaTagihanJamaah,
                'status_pembayaran' => $statusJamaah
            ]);

            TransaksiPembayaran::create([
                'id_jamaah' => $jamaah->id_jamaah,
                'id_metode_pembayaran' => $validated['id_metode_pembayaran'],
                'id_jenis_transaksi' => $validated['id_jenis_transaksi'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'jumlah_bayar' => round($bayarPerJamaah),
                'bukti_pembayaran' => $buktiPath,
                'keterangan' => $validated['keterangan'] . ' (Dari pembayaran keluarga)',
                'created_by' => Auth::user()->name ?? 'System',
            ]);
        }

        return redirect()->route('transaksional.keluarga.show', $id)
            ->with('success', "Pembayaran keluarga sebesar Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil dan telah masuk ke riwayat pembayaran jamaah masing-masing!");
    }
}
