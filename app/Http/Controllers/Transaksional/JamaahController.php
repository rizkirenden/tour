<?php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\JamaahService;
use App\Models\MetodePembayaran;
use App\Models\JenisTransaksi;
use App\Models\TransaksiPembayaran;
use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class JamaahController extends Controller
{
    protected $service;

    public function __construct(JamaahService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status_pembayaran', 'jenis_kelamin']);
        $data = $this->service->getAll($filters);

        if ($request->ajax()) {
            return view('jamaahs.table', compact('data'));
        }

        return view('jamaahs.index', compact('data'));
    }

    public function create()
    {
        $produkPakets = $this->service->getProdukPakets();
        $kotaAsals = $this->service->getKotaAsals();
        $diskons = $this->service->getDiskons();
        return view('jamaahs.create', compact('produkPakets', 'kotaAsals', 'diskons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_keberangkatan' => 'nullable|string|max:100|unique:jamaahs,id_keberangkatan',
            'id_keluarga' => 'nullable|exists:keluargas,id_keluarga',
            'hubungan_keluarga' => 'nullable|string|max:30',
            'is_kepala_keluarga' => 'nullable|boolean',
            'produk_paket' => 'required|string|max:100|exists:produk_pakets,nama_produk',
            'id_diskon' => 'nullable|exists:diskons,id_diskon',
            'nama_lengkap' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nomor_paspor' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'kota_asal' => 'required|string|max:50|exists:kota_asals,nama_kota',
            'pulau' => 'nullable|string|max:20',
            'bandara_keberangkatan' => 'nullable|string|max:50',
            'bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_vaksin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_visa' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_pendampingan' => 'nullable|string|max:30',
            'agent' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',
            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string'
        ]);

        // Upload foto
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = time() . '_ktp.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah-foto', $filename, 'public');
            $validated['foto_ktp'] = $path;
        }

        if ($request->hasFile('foto_vaksin')) {
            $file = $request->file('foto_vaksin');
            $filename = time() . '_vaksin.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah-foto', $filename, 'public');
            $validated['foto_vaksin'] = $path;
        }

        if ($request->hasFile('foto_visa')) {
            $file = $request->file('foto_visa');
            $filename = time() . '_visa.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah-foto', $filename, 'public');
            $validated['foto_visa'] = $path;
        }

        $jamaah = $this->service->create($validated);

        return redirect()->route('transaksional.jamaah.index')
            ->with('success', "Jamaah '{$jamaah->nama_lengkap}' berhasil ditambahkan!");
    }

    public function show($id)
    {
        $jamaah = $this->service->getByIdWithRelations($id);
        return view('jamaahs.show', compact('jamaah'));
    }

    public function edit($id)
    {
        $jamaah = $this->service->getById($id);
        $produkPakets = $this->service->getProdukPakets();
        $kotaAsals = $this->service->getKotaAsals();
        $diskons = $this->service->getDiskons();
        return view('jamaahs.edit', compact('jamaah', 'produkPakets', 'kotaAsals', 'diskons'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_keberangkatan' => ['nullable', 'string', 'max:100', Rule::unique('jamaahs', 'id_keberangkatan')->ignore($id, 'id_jamaah')],
            'id_keluarga' => 'nullable|exists:keluargas,id_keluarga',
            'hubungan_keluarga' => 'nullable|string|max:30',
            'is_kepala_keluarga' => 'nullable|boolean',
            'produk_paket' => 'required|string|max:100|exists:produk_pakets,nama_produk',
            'id_diskon' => 'nullable|exists:diskons,id_diskon',
            'nama_lengkap' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nomor_paspor' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'kota_asal' => 'required|string|max:50|exists:kota_asals,nama_kota',
            'pulau' => 'nullable|string|max:20',
            'bandara_keberangkatan' => 'nullable|string|max:50',
            'bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_vaksin' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_visa' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'jenis_pendampingan' => 'nullable|string|max:30',
            'agent' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',
            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string'
        ]);

        $jamaah = $this->service->getById($id);

        // Upload foto
        if ($request->hasFile('foto_ktp')) {
            if ($jamaah->foto_ktp && Storage::disk('public')->exists($jamaah->foto_ktp)) {
                Storage::disk('public')->delete($jamaah->foto_ktp);
            }
            $file = $request->file('foto_ktp');
            $filename = time() . '_ktp.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah-foto', $filename, 'public');
            $validated['foto_ktp'] = $path;
        }

        if ($request->hasFile('foto_vaksin')) {
            if ($jamaah->foto_vaksin && Storage::disk('public')->exists($jamaah->foto_vaksin)) {
                Storage::disk('public')->delete($jamaah->foto_vaksin);
            }
            $file = $request->file('foto_vaksin');
            $filename = time() . '_vaksin.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah-foto', $filename, 'public');
            $validated['foto_vaksin'] = $path;
        }

        if ($request->hasFile('foto_visa')) {
            if ($jamaah->foto_visa && Storage::disk('public')->exists($jamaah->foto_visa)) {
                Storage::disk('public')->delete($jamaah->foto_visa);
            }
            $file = $request->file('foto_visa');
            $filename = time() . '_visa.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah-foto', $filename, 'public');
            $validated['foto_visa'] = $path;
        }

        $jamaah = $this->service->update($id, $validated);

        return redirect()->route('transaksional.jamaah.index')
            ->with('success', "Jamaah '{$jamaah->nama_lengkap}' berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $jamaah = $this->service->getById($id);

        if ($jamaah->foto_ktp && Storage::disk('public')->exists($jamaah->foto_ktp)) {
            Storage::disk('public')->delete($jamaah->foto_ktp);
        }
        if ($jamaah->foto_vaksin && Storage::disk('public')->exists($jamaah->foto_vaksin)) {
            Storage::disk('public')->delete($jamaah->foto_vaksin);
        }
        if ($jamaah->foto_visa && Storage::disk('public')->exists($jamaah->foto_visa)) {
            Storage::disk('public')->delete($jamaah->foto_visa);
        }

        $nama = $this->service->delete($id);

        return redirect()->route('transaksional.jamaah.index')
            ->with('success', "Jamaah '{$nama}' berhasil dihapus!");
    }

    public function pembayaran($id)
    {
        $jamaah = $this->service->getById($id);
        $metodePembayarans = MetodePembayaran::active()->get();
        $jenisTransaksis = JenisTransaksi::all();
        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisTransaksi'])
                        ->where('id_jamaah', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('jamaahs.pembayaran', compact('jamaah', 'metodePembayarans', 'jenisTransaksis', 'transaksis'));
    }

    public function bayar(Request $request, $id)
    {
        $validated = $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayarans,id_metode',
            'id_jenis_transaksi' => 'required|exists:jenis_transaksis,id_jenis',
            'tanggal_transaksi' => 'required|date',
            'jumlah_bayar' => 'required|integer|min:1',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $jamaah = $this->service->getById($id);
        $totalTagihan = $jamaah->total_tagihan_setelah_diskon;
        $totalDibayarSaatIni = $jamaah->total_dibayar;
        $jumlahBayar = $validated['jumlah_bayar'];
        $jenisTransaksi = JenisTransaksi::find($validated['id_jenis_transaksi']);

        // Validasi: jika jenis transaksi LUNAS, harus bayar sisa tagihan
        if ($jenisTransaksi->kode == 'LUNAS' && $jumlahBayar < ($totalTagihan - $totalDibayarSaatIni)) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar untuk pelunasan harus sebesar sisa tagihan: ' . number_format($totalTagihan - $totalDibayarSaatIni, 0, ',', '.'));
        }

        // Validasi: tidak boleh bayar melebihi sisa tagihan
        if ($jumlahBayar > ($totalTagihan - $totalDibayarSaatIni)) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar melebihi sisa tagihan!');
        }

        // Upload bukti pembayaran
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_bukti_' . $jamaah->id_jamaah . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('bukti-pembayaran', $filename, 'public');
        }

        // Simpan transaksi
        $transaksi = TransaksiPembayaran::create([
            'id_jamaah' => $id,
            'id_metode_pembayaran' => $validated['id_metode_pembayaran'],
            'id_jenis_transaksi' => $validated['id_jenis_transaksi'],
            'tanggal_transaksi' => $validated['tanggal_transaksi'],
            'jumlah_bayar' => $jumlahBayar,
            'bukti_pembayaran' => $buktiPath,
            'keterangan' => $validated['keterangan'],
            'created_by' => Auth::user()->name ?? 'System',
        ]);

        // Update data jamaah
        $totalDibayarBaru = $totalDibayarSaatIni + $jumlahBayar;
        $sisaTagihanBaru = $totalTagihan - $totalDibayarBaru;

        if ($sisaTagihanBaru <= 0) {
            $status = 'Lunas';
        } elseif ($totalDibayarBaru == 0) {
            $status = 'Belum Bayar';
        } elseif ($totalDibayarBaru >= $totalTagihan * 0.5) {
            $status = 'Setoran';
        } else {
            $status = 'DP';
        }

        $jamaah->update([
            'total_dibayar' => $totalDibayarBaru,
            'sisa_tagihan' => $sisaTagihanBaru,
            'status_pembayaran' => $status
        ]);

        // Jika jamaah memiliki keluarga, update total keluarga
        if ($jamaah->id_keluarga) {
            $keluargaService = new \App\Services\KeluargaService();
            $keluargaService->recalculateKeluarga($jamaah->id_keluarga);
        }

        return redirect()->route('transaksional.jamaah.pembayaran', $id)
            ->with('success', "Pembayaran {$jenisTransaksi->nama} sebesar Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil!");
    }

    public function hapusBukti($id)
    {
        $transaksi = TransaksiPembayaran::findOrFail($id);

        if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
            $transaksi->update(['bukti_pembayaran' => null]);

            return redirect()->back()
                ->with('success', 'Bukti pembayaran berhasil dihapus!');
        }

        return redirect()->back()
            ->with('error', 'Bukti pembayaran tidak ditemukan!');
    }

    public function hapusTransaksi($id)
    {
        $transaksi = TransaksiPembayaran::findOrFail($id);
        $jamaahId = $transaksi->id_jamaah;
        $jumlahBayar = $transaksi->jumlah_bayar;

        // Hapus file bukti
        if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
        }

        $transaksi->delete();

        // Recalculate jamaah
        $jamaah = $this->service->getById($jamaahId);
        $totalTagihan = $jamaah->total_tagihan_setelah_diskon;
        $totalDibayarBaru = $jamaah->transaksis()->sum('jumlah_bayar');
        $sisaTagihanBaru = $totalTagihan - $totalDibayarBaru;

        if ($sisaTagihanBaru <= 0) {
            $status = 'Lunas';
        } elseif ($totalDibayarBaru == 0) {
            $status = 'Belum Bayar';
        } elseif ($totalDibayarBaru >= $totalTagihan * 0.5) {
            $status = 'Setoran';
        } else {
            $status = 'DP';
        }

        $jamaah->update([
            'total_dibayar' => $totalDibayarBaru,
            'sisa_tagihan' => $sisaTagihanBaru,
            'status_pembayaran' => $status
        ]);

        // Jika jamaah memiliki keluarga, update total keluarga
        if ($jamaah->id_keluarga) {
            $keluargaService = new \App\Services\KeluargaService();
            $keluargaService->recalculateKeluarga($jamaah->id_keluarga);
        }

        return redirect()->route('transaksional.jamaah.pembayaran', $jamaahId)
            ->with('success', 'Transaksi pembayaran berhasil dihapus!');
    }
}
