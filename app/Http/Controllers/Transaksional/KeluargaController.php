<?php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\KeluargaService;
use App\Models\TransaksiPembayaran;
use App\Models\MetodePembayaran;
use App\Models\ProdukPaket;
use App\Models\ProdukKeberangkatan;
use App\Models\ProdukHargaBulanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DokumenPerusahaan;

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
            'id_produk_keberangkatan' => 'nullable|exists:produk_keberangkatans,id',

            'bulan_harga' => 'nullable|integer|min:1|max:12',
            'tahun_harga' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'id_produk_harga_bulanan' => 'nullable|exists:produk_harga_bulanan,id',

            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',

            'jamaahs' => 'required|array|min:1',
            'jamaahs.*.nama_lengkap' => 'required|string|max:100',
            'jamaahs.*.nik' => 'required|string|max:20',
            'jamaahs.*.nama_ayah' => 'required|string|max:100',
            'jamaahs.*.pekerjaan' => 'required|string|max:100',
            'jamaahs.*.hubungan_keluarga' => 'nullable|string|max:30',
            'jamaahs.*.is_kepala_keluarga' => 'nullable|boolean',
            'jamaahs.*.jenis_kelamin' => 'required|in:L,P',
            'jamaahs.*.telepon' => 'required|string|max:20',
            'jamaahs.*.wa' => 'required|string|max:20',
            'jamaahs.*.alamat' => 'nullable|string',
            'jamaahs.*.kota_asal' => 'nullable|string|max:50|exists:kota_asals,nama_kota',
            'jamaahs.*.pulau' => 'nullable|string|max:20',
            'jamaahs.*.bandara_keberangkatan' => 'nullable|string|max:50',
            'jamaahs.*.nomor_paspor' => 'nullable|string|max:20',
            'jamaahs.*.tanggal_lahir' => 'nullable|date',
            'jamaahs.*.tempat_lahir' => 'required|string|max:100',
            'jamaahs.*.file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.pendampingan_nama' => 'nullable|string|max:100',
            'jamaahs.*.pendampingan_fee' => 'nullable|integer|min:0',
            'jamaahs.*.pendampingan_fee_petugas' => 'nullable|integer|min:0',
        ]);

        try {
            $keluarga = $this->service->create($validated);

            return redirect()->route('transaksional.keluarga.index')
                ->with('success', "Keluarga '{$keluarga->nama_keluarga}' berhasil ditambahkan!");
        } catch (\Exception $e) {
            $this->cleanupUploadedFiles($validated);

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $keluarga = $this->service->getById($id);

        $jamaahIds = $keluarga->jamaahs->pluck('id_jamaah')->toArray();
        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisTransaksi', 'jamaah'])
            ->whereIn('id_jamaah', $jamaahIds)
            ->orderBy('created_at', 'asc')
            ->get();

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
            'id_produk_keberangkatan' => 'nullable|exists:produk_keberangkatans,id',

            'bulan_harga' => 'nullable|integer|min:1|max:12',
            'tahun_harga' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'id_produk_harga_bulanan' => 'nullable|exists:produk_harga_bulanan,id',

            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',

            'jamaahs' => 'required|array|min:1',
            'jamaahs.*.id_jamaah' => 'nullable|exists:jamaahs,id_jamaah',
            'jamaahs.*.nama_lengkap' => 'required|string|max:100',
            'jamaahs.*.nik' => 'required|string|max:20',
            'jamaahs.*.nama_ayah' => 'required|string|max:100',
            'jamaahs.*.pekerjaan' => 'required|string|max:100',
            'jamaahs.*.hubungan_keluarga' => 'nullable|string|max:30',
            'jamaahs.*.is_kepala_keluarga' => 'nullable|boolean',
            'jamaahs.*.jenis_kelamin' => 'required|in:L,P',
            'jamaahs.*.telepon' => 'required|string|max:20',
            'jamaahs.*.wa' => 'required|string|max:20',
            'jamaahs.*.alamat' => 'nullable|string',
            'jamaahs.*.kota_asal' => 'nullable|string|max:50|exists:kota_asals,nama_kota',
            'jamaahs.*.pulau' => 'nullable|string|max:20',
            'jamaahs.*.bandara_keberangkatan' => 'nullable|string|max:50',
            'jamaahs.*.nomor_paspor' => 'nullable|string|max:20',
            'jamaahs.*.tanggal_lahir' => 'nullable|date',
            'jamaahs.*.tempat_lahir' => 'required|string|max:100',
            'jamaahs.*.file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.pendampingan_nama' => 'nullable|string|max:100',
            'jamaahs.*.pendampingan_fee' => 'nullable|integer|min:0',
            'jamaahs.*.pendampingan_fee_petugas' => 'nullable|integer|min:0',
        ]);

        try {
            $keluarga = $this->service->update($id, $validated);

            return redirect()->route('transaksional.keluarga.index')
                ->with('success', "Keluarga '{$keluarga->nama_keluarga}' berhasil diperbarui!");
        } catch (\Exception $e) {
            $this->cleanupUploadedFiles($validated);

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    private function cleanupUploadedFiles(array $validated)
    {
        if (empty($validated['jamaahs'])) return;

        foreach ($validated['jamaahs'] as $jamaah) {
            foreach (['file_ktp_kk', 'file_vaksin', 'file_visa', 'file_paspor'] as $field) {
                if (!empty($jamaah[$field]) && is_string($jamaah[$field]) && Storage::disk('public')->exists($jamaah[$field])) {
                    Storage::disk('public')->delete($jamaah[$field]);
                }
            }
        }
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
        $metodePembayarans = MetodePembayaran::active()->get();
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
            'bukti_pembayaran' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
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

    // ==========================================
// HAPUS SATU TRANSAKSI PEMBAYARAN KELUARGA
// ==========================================
public function hapusTransaksi($idTransaksi)
{
    return DB::transaction(function () use ($idTransaksi) {
        $transaksi = TransaksiPembayaran::findOrFail($idTransaksi);

        $jamaah = \App\Models\Jamaah::find($transaksi->id_jamaah);
        $keluarga = $jamaah ? \App\Models\Keluarga::find($jamaah->id_keluarga) : null;

        // 1. Kembalikan total dibayar & sisa tagihan jamaah
        if ($jamaah) {
            $totalDibayarBaru = max(0, $jamaah->total_dibayar - $transaksi->jumlah_bayar);
            $sisaTagihanBaru = $jamaah->total_tagihan_setelah_diskon - $totalDibayarBaru;

            if ($sisaTagihanBaru <= 0) {
                $statusJamaah = 'Lunas';
            } elseif ($totalDibayarBaru == 0) {
                $statusJamaah = 'Belum Bayar';
            } elseif ($totalDibayarBaru >= $jamaah->total_tagihan_setelah_diskon * 0.5) {
                $statusJamaah = 'Setoran';
            } else {
                $statusJamaah = 'DP';
            }

            $jamaah->update([
                'total_dibayar' => $totalDibayarBaru,
                'sisa_tagihan' => $sisaTagihanBaru,
                'status_pembayaran' => $statusJamaah,
            ]);
        }

        // 2. Hapus file bukti dari storage
        if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
        }

        // 3. Hapus baris transaksi
        $transaksi->delete();

        // 4. Recalculate keluarga (total, sisa, status)
        if ($keluarga) {
            $this->service->recalculateKeluarga($keluarga->id_keluarga);
        }

        return redirect()->route('transaksional.keluarga.show', $keluarga->id_keluarga ?? null)
            ->with('success', 'Transaksi pembayaran berhasil dihapus dan total tagihan telah diperbarui.');
    });
}

    public function cetakPembayaran($id)
    {
        $keluarga = $this->service->getById($id);

        $jamaahIds = $keluarga->jamaahs->pluck('id_jamaah')->toArray();
        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisTransaksi', 'jamaah'])
            ->whereIn('id_jamaah', $jamaahIds)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($transaksis as $transaksi) {
            if ($transaksi->bukti_pembayaran) {
                $path = storage_path('app/public/' . $transaksi->bukti_pembayaran);
                if (file_exists($path)) {
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp']);
                    if ($isImage) {
                        $imageData = base64_encode(file_get_contents($path));
                        $transaksi->bukti_base64 = 'data:image/' . $extension . ';base64,' . $imageData;
                        $transaksi->bukti_exists = true;
                    } else {
                        $transaksi->bukti_base64 = null;
                        $transaksi->bukti_exists = true;
                        $transaksi->is_pdf_file = strtolower($extension) == 'pdf';
                    }
                    $transaksi->bukti_extension = $extension;
                    $transaksi->bukti_name = basename($transaksi->bukti_pembayaran);
                } else {
                    $transaksi->bukti_exists = false;
                }
            } else {
                $transaksi->bukti_exists = false;
            }
        }

        $metodePembayarans = MetodePembayaran::active()
            ->orderBy('jenis_pembayaran')
            ->orderBy('nama_bank')
            ->get();

        $ttdData = $this->loadDokumenTTD();

        $data = [
            'keluarga'          => $keluarga,
            'transaksis'        => $transaksis,
            'total_transaksi'   => $transaksis->sum('jumlah_bayar'),
            'tanggal_cetak'     => date('d/m/Y H:i'),
            'dicetak_oleh'      => Auth::user()->name ?? 'System',
            'metodePembayarans' => $metodePembayarans,
            'logoBase64'        => $this->loadDokumenBase64('logo'),
            'ttdBase64'         => $ttdData['base64'],
            'ttdNama'           => $ttdData['nama'],
            'ttdJabatan'        => $ttdData['jabatan'],
            'capBase64'         => $this->loadDokumenBase64('cap'),
        ];

        $pdf = Pdf::loadView('keluargas.pdf-riwayat-keluarga', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'isPhpEnabled' => false,
            'chroot' => public_path(),
        ]);

        return $pdf->download('Riwayat_Pembayaran_Keluarga_' . $keluarga->kode_keluarga . '.pdf');
    }

    private function loadDokumenBase64($jenis)
    {
        $dokumen = DokumenPerusahaan::where('jenis', $jenis)->first();

        if (!$dokumen || !$dokumen->path) {
            if ($jenis == 'logo') {
                $fallbackPath = public_path('assets/logo.PNG');
                if (file_exists($fallbackPath) && filesize($fallbackPath) < 500000) {
                    $data = file_get_contents($fallbackPath);
                    return 'data:image/png;base64,' . base64_encode($data);
                }
            }
            return null;
        }

        $fullPath = storage_path('app/public/' . $dokumen->path);
        if (!file_exists($fullPath)) {
            return null;
        }

        $data = file_get_contents($fullPath);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);

        return 'data:' . $mimeType . ';base64,' . base64_encode($data);
    }

    private function loadDokumenTTD()
    {
        $dokumen = DokumenPerusahaan::where('jenis', 'ttd')->first();

        if (!$dokumen || !$dokumen->path) {
            return ['base64' => null, 'nama' => null, 'jabatan' => null];
        }

        $fullPath = storage_path('app/public/' . $dokumen->path);
        if (!file_exists($fullPath)) {
            return [
                'base64' => null,
                'nama' => $dokumen->nama_penandatangan,
                'jabatan' => $dokumen->jabatan,
            ];
        }

        $data = file_get_contents($fullPath);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);

        return [
            'base64' => 'data:' . $mimeType . ';base64,' . base64_encode($data),
            'nama' => $dokumen->nama_penandatangan,
            'jabatan' => $dokumen->jabatan,
        ];
    }

    public function getKeberangkatanByProduk(Request $request)
    {
        $request->validate([
            'produk_paket' => 'required|string|exists:produk_pakets,nama_produk',
        ]);

        $data = $this->service->getKeberangkatanByProduk($request->produk_paket);

        return response()->json([
            'success' => true,
            'keberangkatan' => $data['keberangkatan'],
            'harga_bulanan' => $data['harga_bulanan'],
        ]);
    }

    public function getHargaProdukByBulan(Request $request)
    {
        $request->validate([
            'produk_paket' => 'required|string|exists:produk_pakets,nama_produk',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000',
        ]);

        $produk = ProdukPaket::where('nama_produk', $request->produk_paket)->first();
        if (!$produk) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $harga = ProdukHargaBulanan::where('produk_paket_id', $produk->id_produk)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('is_active', true)
            ->first();

        if ($harga) {
            return response()->json([
                'success' => true,
                'harga' => $harga->harga,
                'harga_formatted' => $harga->harga_formatted,
                'bulan_formatted' => $harga->bulan_formatted,
                'tahun' => $harga->tahun,
                'kuota' => $harga->kuota,
                'kuota_formatted' => $harga->kuota_formatted,
                'kuota_terpakai' => $harga->terpakai,
                'kuota_sisa' => $harga->sisa_kuota,
                'is_full' => $harga->is_full,
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Harga tidak tersedia untuk bulan/tahun ini'
        ], 404);
    }
}
