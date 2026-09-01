<?php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\JamaahService;
use App\Models\MetodePembayaran;
use App\Models\JenisTransaksi;
use App\Models\TransaksiPembayaran;
use App\Models\Diskon;
use App\Models\ProdukPaket;
use App\Models\ProdukHargaBulanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class JamaahController extends Controller
{
    protected $service;

    public function __construct(JamaahService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status_pembayaran', 'jenis_kelamin', 'sumber_data']);
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
            // ==========================================
            // NIK - TIDAK ADA VALIDASI UNIQUE
            // ==========================================
            'nik' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nomor_paspor' => 'nullable|string|max:20',
            'paspor_expired' => 'nullable|date',
            'paspor_terbit' => 'nullable|date',
            'paspor_diterbitkan_di' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'kota_asal' => 'required|string|max:50|exists:kota_asals,nama_kota',
            'pulau' => 'nullable|string|max:20',
            'bandara_keberangkatan' => 'nullable|string|max:50',
            'bulan_keberangkatan' => 'required|integer|min:1|max:12',
            'tahun_keberangkatan' => 'required|integer|min:2000|max:' . (date('Y') + 10),
            'file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jenis_pendampingan' => 'nullable|string|max:30',
            'agent_name' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',
            'pendampingan_nama' => 'nullable|string|max:100',
            'pendampingan_fee' => 'nullable|integer|min:0',
            'pendampingan_fee_petugas' => 'nullable|integer|min:0',
            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string'
        ]);

        // Convert fee fields to integer
        $validated['fee_agent'] = (int) ($validated['fee_agent'] ?? 0);
        $validated['pendampingan_fee'] = (int) ($validated['pendampingan_fee'] ?? 0);
        $validated['pendampingan_fee_petugas'] = (int) ($validated['pendampingan_fee_petugas'] ?? 0);

        // Upload File KTP/KK
        if ($request->hasFile('file_ktp_kk')) {
            $file = $request->file('file_ktp_kk');
            $filename = time() . '_ktp_kk.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_ktp_kk'] = $path;
        }

        // Upload File Vaksin
        if ($request->hasFile('file_vaksin')) {
            $file = $request->file('file_vaksin');
            $filename = time() . '_vaksin.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_vaksin'] = $path;
        }

        // Upload File Visa
        if ($request->hasFile('file_visa')) {
            $file = $request->file('file_visa');
            $filename = time() . '_visa.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_visa'] = $path;
        }

        // Upload File Paspor
        if ($request->hasFile('file_paspor')) {
            $file = $request->file('file_paspor');
            $filename = time() . '_paspor.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_paspor'] = $path;
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
        $jamaah = $this->service->getById($id);

        $validated = $request->validate([
            'id_keberangkatan' => ['nullable', 'string', 'max:100', Rule::unique('jamaahs', 'id_keberangkatan')->ignore($id, 'id_jamaah')],
            'id_keluarga' => 'nullable|exists:keluargas,id_keluarga',
            'hubungan_keluarga' => 'nullable|string|max:30',
            'is_kepala_keluarga' => 'nullable|boolean',
            'produk_paket' => 'required|string|max:100|exists:produk_pakets,nama_produk',
            'id_diskon' => 'nullable|exists:diskons,id_diskon',
            'nama_lengkap' => 'required|string|max:100',
            // ==========================================
            // NIK - TIDAK ADA VALIDASI UNIQUE DI UPDATE
            // ==========================================
            'nik' => ['nullable', 'string', 'max:20'],
            'nama_ayah' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'nomor_paspor' => 'nullable|string|max:20',
            'paspor_expired' => 'nullable|date',
            'paspor_terbit' => 'nullable|date',
            'paspor_diterbitkan_di' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'kota_asal' => 'required|string|max:50|exists:kota_asals,nama_kota',
            'pulau' => 'nullable|string|max:20',
            'bandara_keberangkatan' => 'nullable|string|max:50',
            'bulan_keberangkatan' => 'required|integer|min:1|max:12',
            'tahun_keberangkatan' => 'required|integer|min:2000|max:' . (date('Y') + 10),
            'file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jenis_pendampingan' => 'nullable|string|max:30',
            'agent_name' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',
            'pendampingan_nama' => 'nullable|string|max:100',
            'pendampingan_fee' => 'nullable|integer|min:0',
            'pendampingan_fee_petugas' => 'nullable|integer|min:0',
            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string'
        ]);

        // Convert fee fields to integer
        $validated['fee_agent'] = (int) ($validated['fee_agent'] ?? 0);
        $validated['pendampingan_fee'] = (int) ($validated['pendampingan_fee'] ?? 0);
        $validated['pendampingan_fee_petugas'] = (int) ($validated['pendampingan_fee_petugas'] ?? 0);

        // Handle file uploads with delete old files
        $this->handleFileUploads($request, $jamaah, $validated);

        $jamaah = $this->service->update($id, $validated);

        return redirect()->route('transaksional.jamaah.index')
            ->with('success', "Jamaah '{$jamaah->nama_lengkap}' berhasil diperbarui!");
    }

    private function handleFileUploads(Request $request, $jamaah, array &$validated)
    {
        // File KTP/KK
        if ($request->hasFile('file_ktp_kk')) {
            if ($jamaah->file_ktp_kk && Storage::disk('public')->exists($jamaah->file_ktp_kk)) {
                Storage::disk('public')->delete($jamaah->file_ktp_kk);
            }
            $file = $request->file('file_ktp_kk');
            $filename = time() . '_ktp_kk.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_ktp_kk'] = $path;
        }

        // File Vaksin
        if ($request->hasFile('file_vaksin')) {
            if ($jamaah->file_vaksin && Storage::disk('public')->exists($jamaah->file_vaksin)) {
                Storage::disk('public')->delete($jamaah->file_vaksin);
            }
            $file = $request->file('file_vaksin');
            $filename = time() . '_vaksin.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_vaksin'] = $path;
        }

        // File Visa
        if ($request->hasFile('file_visa')) {
            if ($jamaah->file_visa && Storage::disk('public')->exists($jamaah->file_visa)) {
                Storage::disk('public')->delete($jamaah->file_visa);
            }
            $file = $request->file('file_visa');
            $filename = time() . '_visa.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_visa'] = $path;
        }

        // File Paspor
        if ($request->hasFile('file_paspor')) {
            if ($jamaah->file_paspor && Storage::disk('public')->exists($jamaah->file_paspor)) {
                Storage::disk('public')->delete($jamaah->file_paspor);
            }
            $file = $request->file('file_paspor');
            $filename = time() . '_paspor.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
            $validated['file_paspor'] = $path;
        }
    }

    public function destroy($id)
    {
        $jamaah = $this->service->getById($id);

        // Delete all files
        $files = ['file_ktp_kk', 'file_vaksin', 'file_visa', 'file_paspor'];
        foreach ($files as $fileField) {
            if ($jamaah->$fileField && Storage::disk('public')->exists($jamaah->$fileField)) {
                Storage::disk('public')->delete($jamaah->$fileField);
            }
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
                        ->orderBy('created_at', 'asc')
                        ->get();

        return view('jamaahs.pembayaran', compact('jamaah', 'metodePembayarans', 'jenisTransaksis', 'transaksis'));
    }

    public function bayar(Request $request, $id)
    {
        // Clean jumlah_bayar from formatting (remove dots)
        $jumlahBayarRaw = preg_replace('/[^\d]/', '', $request->input('jumlah_bayar'));
        $request->merge(['jumlah_bayar' => (int) $jumlahBayarRaw]);

        $validated = $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayarans,id_metode',
            'id_jenis_transaksi' => 'required|exists:jenis_transaksis,id_jenis',
            'tanggal_transaksi' => 'required|date',
            'jumlah_bayar' => 'required|integer|min:1',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'keterangan' => 'nullable|string'
        ]);

        $jamaah = $this->service->getById($id);
        $totalTagihan = $jamaah->total_tagihan_setelah_diskon;
        $totalDibayarSaatIni = $jamaah->total_dibayar;
        $jumlahBayar = $validated['jumlah_bayar'];
        $jenisTransaksi = JenisTransaksi::find($validated['id_jenis_transaksi']);

        // Validate based on transaction type
        if ($jenisTransaksi->kode == 'LUNAS' && $jumlahBayar < ($totalTagihan - $totalDibayarSaatIni)) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar untuk pelunasan harus sebesar sisa tagihan: Rp ' . number_format($totalTagihan - $totalDibayarSaatIni, 0, ',', '.'));
        }

        if ($jumlahBayar > ($totalTagihan - $totalDibayarSaatIni)) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar melebihi sisa tagihan!');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_bukti_' . $jamaah->id_jamaah . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('jamaah/bukti-pembayaran', $filename, 'public');
        }

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

        $totalDibayarBaru = $totalDibayarSaatIni + $jumlahBayar;
        $sisaTagihanBaru = $totalTagihan - $totalDibayarBaru;

        // Determine payment status
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

        // Update keluarga if exists
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

        // Delete file bukti
        if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
        }

        $transaksi->delete();

        // Update jamaah
        $jamaah = $this->service->getById($jamaahId);
        $totalTagihan = $jamaah->total_tagihan_setelah_diskon;
        $totalDibayarBaru = $jamaah->transaksis()->sum('jumlah_bayar');
        $sisaTagihanBaru = $totalTagihan - $totalDibayarBaru;

        // Determine payment status
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

        // Update keluarga if exists
        if ($jamaah->id_keluarga) {
            $keluargaService = new \App\Services\KeluargaService();
            $keluargaService->recalculateKeluarga($jamaah->id_keluarga);
        }

        return redirect()->route('transaksional.jamaah.pembayaran', $jamaahId)
            ->with('success', 'Transaksi pembayaran berhasil dihapus!');
    }

    // ==========================================
    // CETAK PDF RIWAYAT PEMBAYARAN DENGAN BASE64
    // ==========================================
    public function cetakPdfRiwayat($id)
    {
        // Ambil data jamaah dengan relasi
        $jamaah = $this->service->getByIdWithRelations($id);

        // Ambil transaksi dengan relasi
        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisTransaksi'])
                        ->where('id_jamaah', $id)
                        ->orderBy('created_at', 'asc')
                        ->get();

        // Konversi gambar bukti ke base64
        foreach ($transaksis as $transaksi) {
            if ($transaksi->bukti_pembayaran) {
                $fullPath = storage_path('app/public/' . $transaksi->bukti_pembayaran);

                // Cek apakah file exists
                if (file_exists($fullPath)) {
                    $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                    $mimeType = mime_content_type($fullPath);

                    // Baca file dan konversi ke base64
                    $imageData = base64_encode(file_get_contents($fullPath));

                    // Simpan base64 ke dalam object transaksi
                    $transaksi->bukti_base64 = 'data:' . $mimeType . ';base64,' . $imageData;
                    $transaksi->bukti_extension = $extension;
                    $transaksi->bukti_name = basename($transaksi->bukti_pembayaran);
                    $transaksi->bukti_exists = true;
                } else {
                    $transaksi->bukti_exists = false;
                    $transaksi->bukti_base64 = null;
                }
            } else {
                $transaksi->bukti_exists = false;
                $transaksi->bukti_base64 = null;
            }
        }

        $data = [
            'jamaah' => $jamaah,
            'transaksis' => $transaksis,
            'total_transaksi' => $transaksis->sum('jumlah_bayar'),
            'tanggal_cetak' => now()->format('d/m/Y H:i:s'),
            'dicetak_oleh' => Auth::user()->name ?? 'System'
        ];

        $pdf = Pdf::loadView('jamaahs.pdf-riwayat', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true
        ]);

        return $pdf->download('Riwayat_Pembayaran_' . $jamaah->nama_lengkap . '_' . date('Ymd_His') . '.pdf');
    }

    // ==========================================
    // GET HARGA PRODUK BY BULAN & TAHUN (AJAX)
    // ==========================================
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

        // Cari harga berdasarkan bulan dan tahun
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
                'flyer' => $harga->flyer_url,
                'bulan_formatted' => $harga->bulan_formatted,
                'tahun' => $harga->tahun,
            ]);
        }

        // Jika tidak ada harga untuk bulan/tahun tersebut, cari harga pertama yang aktif
        $hargaDefault = $produk->hargaBulanan()->active()->first();
        if ($hargaDefault) {
            return response()->json([
                'success' => true,
                'harga' => $hargaDefault->harga,
                'harga_formatted' => $hargaDefault->harga_formatted,
                'flyer' => $hargaDefault->flyer_url,
                'bulan_formatted' => $hargaDefault->bulan_formatted,
                'tahun' => $hargaDefault->tahun,
                'warning' => 'Harga untuk bulan/tahun yang dipilih tidak tersedia, menggunakan harga default'
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Tidak ada harga yang tersedia untuk produk ini'
        ], 404);
    }
}
