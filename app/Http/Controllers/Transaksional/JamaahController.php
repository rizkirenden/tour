<?php
// app/Http/Controllers/Transaksional/JamaahController.php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\JamaahService;
use App\Models\MetodePembayaran;
use App\Models\JenisTransaksi;
use App\Models\JenisPembayaran;
use App\Models\TransaksiPembayaran;
use App\Models\Diskon;
use App\Models\Hotel;
use App\Models\Kamar;
use App\Models\ProdukPaket;
use App\Models\ProdukHargaBulanan;
use App\Models\ProdukKeberangkatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $filters = $request->only(['search', 'status_pembayaran', 'jenis_kelamin', 'sumber_data', 'status_kawin', 'has_kelebihan']);
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
        $hotelOptions = Hotel::orderBy('kota')->orderBy('nama_hotel')->get();

        return view('jamaahs.create', compact(
            'produkPakets',
            'kotaAsals',
            'diskons',
            'hotelOptions'
        ));
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
            'nik' => 'nullable|string|max:20',
            'nama_ayah' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kota_kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'nomor_paspor' => 'nullable|string|max:20',
            'paspor_expired' => 'nullable|date',
            'paspor_terbit' => 'nullable|date',
            'paspor_diterbitkan_di' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'status_kawin' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'kota_asal' => 'required|string|max:50|exists:kota_asals,nama_kota',
            'pulau' => 'nullable|string|max:20',
            'bandara_keberangkatan' => 'nullable|string|max:50',
            'bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),

            'keberangkatan_data' => 'nullable|array',
            'keberangkatan_data.*.id_produk_keberangkatan' => 'required_with:keberangkatan_data|exists:produk_keberangkatans,id',
            'keberangkatan_data.*.id_produk_harga_bulanan' => 'nullable|exists:produk_harga_bulanan,id',
            'keberangkatan_data.*.bulan_harga' => 'nullable|integer|min:1|max:12',
            'keberangkatan_data.*.tahun_harga' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keberangkatan_data.*.harga' => 'nullable|integer|min:0',

            'file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',

            'agent_name' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',

            'is_pembuatan_passport' => 'nullable|boolean',
            'pendampingan_fee' => 'nullable|integer|min:0',
            'pendampingan_fee_petugas' => 'nullable|integer|min:0',

            'upgrade_hotels' => 'nullable|array',
            'upgrade_hotels.*.id_hotel' => 'required_with:upgrade_hotels|exists:hotels,id_hotel',
            'upgrade_hotels.*.id_kamar' => 'nullable|exists:kamars,id_kamar',
            'upgrade_hotels.*.tipe_kamar' => 'nullable|string|max:100',
            'upgrade_hotels.*.jumlah_kamar' => 'nullable|integer|min:1',
            'upgrade_hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'upgrade_hotels.*.harga_per_malam' => 'nullable|integer|min:0',
            'upgrade_hotels.*.catatan' => 'nullable|string',

            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string'
        ]);

        $validated['fee_agent'] = (int) ($validated['fee_agent'] ?? 0);
        $validated['is_pembuatan_passport'] = (bool) ($validated['is_pembuatan_passport'] ?? false);
        $validated['pendampingan_fee'] = (int) ($validated['pendampingan_fee'] ?? 0);
        $validated['pendampingan_fee_petugas'] = (int) ($validated['pendampingan_fee_petugas'] ?? 0);

        if (!$validated['is_pembuatan_passport']) {
            $validated['pendampingan_fee_petugas'] = 0;
        }

        $validated['status_kawin'] = $validated['status_kawin'] ?? null;

        $this->uploadFiles($request, $validated);

        try {
            $jamaah = $this->service->create($validated);

            return redirect()->route('transaksional.jamaah.index')
                ->with('success', "Jamaah '{$jamaah->nama_lengkap}' berhasil ditambahkan!");
        } catch (\Exception $e) {
            $this->cleanupFiles($validated);

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
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
        $hotelOptions = Hotel::orderBy('kota')->orderBy('nama_hotel')->get();

        $selectedKeberangkatan = $jamaah->keberangkatan->map(function ($k) {
            return [
                'id_produk_keberangkatan' => (int) $k->id,
                'id_produk_harga_bulanan' => $k->pivot->id_produk_harga_bulanan
                    ? (int) $k->pivot->id_produk_harga_bulanan
                    : null,
                'bulan_keberangkatan'     => $k->pivot->bulan_keberangkatan
                    ? (int) $k->pivot->bulan_keberangkatan
                    : (int) $k->bulan,
                'tahun_keberangkatan'     => $k->pivot->tahun_keberangkatan
                    ? (int) $k->pivot->tahun_keberangkatan
                    : (int) $k->tahun,
                'bulan_harga'             => $k->pivot->bulan_harga
                    ? (int) $k->pivot->bulan_harga
                    : (int) $k->bulan,
                'tahun_harga'             => $k->pivot->tahun_harga
                    ? (int) $k->pivot->tahun_harga
                    : (int) $k->tahun,
                'harga'                   => (int) ($k->pivot->harga ?? 0),
            ];
        })->values()->toArray();

        $selectedUpgradeHotels = $jamaah->upgradeHotels->map(function ($u) {
            return [
                'id_hotel' => $u->id_hotel,
                'id_kamar' => $u->id_kamar,
                'tipe_kamar' => $u->tipe_kamar,
                'jumlah_kamar' => $u->jumlah_kamar,
                'durasi_menginap' => $u->durasi_menginap,
                'harga_per_malam' => $u->harga_per_malam,
            ];
        })->toArray();

        return view('jamaahs.edit', compact(
            'jamaah',
            'produkPakets',
            'kotaAsals',
            'diskons',
            'hotelOptions',
            'selectedKeberangkatan',
            'selectedUpgradeHotels'
        ));
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
            'nik' => ['nullable', 'string', 'max:20'],
            'nama_ayah' => 'nullable|string|max:100',
            'pekerjaan' => 'nullable|string|max:100',
            'wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'kota_kabupaten' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'nomor_paspor' => 'nullable|string|max:20',
            'paspor_expired' => 'nullable|date',
            'paspor_terbit' => 'nullable|date',
            'paspor_diterbitkan_di' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'status_kawin' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'kota_asal' => 'required|string|max:50|exists:kota_asals,nama_kota',
            'pulau' => 'nullable|string|max:20',
            'bandara_keberangkatan' => 'nullable|string|max:50',
            'bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),

            'keberangkatan_data' => 'nullable|array',
            'keberangkatan_data.*.id_produk_keberangkatan' => 'required_with:keberangkatan_data|exists:produk_keberangkatans,id',
            'keberangkatan_data.*.id_produk_harga_bulanan' => 'nullable|exists:produk_harga_bulanan,id',
            'keberangkatan_data.*.bulan_harga' => 'nullable|integer|min:1|max:12',
            'keberangkatan_data.*.tahun_harga' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keberangkatan_data.*.harga' => 'nullable|integer|min:0',

            'file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',

            'agent_name' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',

            'is_pembuatan_passport' => 'nullable|boolean',
            'pendampingan_fee' => 'nullable|integer|min:0',
            'pendampingan_fee_petugas' => 'nullable|integer|min:0',

            'upgrade_hotels' => 'nullable|array',
            'upgrade_hotels.*.id_hotel' => 'required_with:upgrade_hotels|exists:hotels,id_hotel',
            'upgrade_hotels.*.id_kamar' => 'nullable|exists:kamars,id_kamar',
            'upgrade_hotels.*.tipe_kamar' => 'nullable|string|max:100',
            'upgrade_hotels.*.jumlah_kamar' => 'nullable|integer|min:1',
            'upgrade_hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'upgrade_hotels.*.harga_per_malam' => 'nullable|integer|min:0',
            'upgrade_hotels.*.catatan' => 'nullable|string',

            'keterangan_diskon' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string'
        ]);

        $validated['fee_agent'] = (int) ($validated['fee_agent'] ?? 0);
        $validated['is_pembuatan_passport'] = (bool) ($validated['is_pembuatan_passport'] ?? false);
        $validated['pendampingan_fee'] = (int) ($validated['pendampingan_fee'] ?? 0);
        $validated['pendampingan_fee_petugas'] = (int) ($validated['pendampingan_fee_petugas'] ?? 0);

        if (!$validated['is_pembuatan_passport']) {
            $validated['pendampingan_fee_petugas'] = 0;
        }

        $validated['status_kawin'] = $validated['status_kawin'] ?? null;

        $oldFiles = [
            'file_ktp_kk' => $jamaah->file_ktp_kk,
            'file_vaksin' => $jamaah->file_vaksin,
            'file_visa' => $jamaah->file_visa,
            'file_paspor' => $jamaah->file_paspor,
        ];

        $this->handleFileUploads($request, $jamaah, $validated);

        try {
            $jamaah = $this->service->update($id, $validated);

            $msg = "Jamaah '{$jamaah->nama_lengkap}' berhasil diperbarui!";

            if ($jamaah->sisa_kelebihan_bayar > 0) {
                $msg .= " ⚠️ Ada kelebihan bayar: {$jamaah->sisa_kelebihan_bayar_formatted} — silakan proses refund di halaman pembayaran.";
            }

            return redirect()->route('transaksional.jamaah.index')
                ->with('success', $msg);
        } catch (\Exception $e) {
            foreach (['file_ktp_kk', 'file_vaksin', 'file_visa', 'file_paspor'] as $field) {
                if (isset($validated[$field]) && $validated[$field] !== $oldFiles[$field]) {
                    if ($validated[$field] && Storage::disk('public')->exists($validated[$field])) {
                        Storage::disk('public')->delete($validated[$field]);
                    }
                }
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    private function cleanupFiles(array $validated)
    {
        foreach (['file_ktp_kk', 'file_vaksin', 'file_visa', 'file_paspor'] as $field) {
            if (!empty($validated[$field]) && Storage::disk('public')->exists($validated[$field])) {
                Storage::disk('public')->delete($validated[$field]);
            }
        }
    }

    private function uploadFiles(Request $request, array &$validated)
    {
        $fileFields = ['file_ktp_kk', 'file_vaksin', 'file_visa', 'file_paspor'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . str_replace('file_', '', $field) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
                $validated[$field] = $path;
            }
        }
    }

    private function handleFileUploads(Request $request, $jamaah, array &$validated)
    {
        $fileFields = [
            'file_ktp_kk' => 'ktp_kk',
            'file_vaksin' => 'vaksin',
            'file_visa' => 'visa',
            'file_paspor' => 'paspor',
        ];

        foreach ($fileFields as $field => $suffix) {
            if ($request->hasFile($field)) {
                if ($jamaah->$field && Storage::disk('public')->exists($jamaah->$field)) {
                    Storage::disk('public')->delete($jamaah->$field);
                }
                $file = $request->file($field);
                $filename = time() . '_' . $suffix . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('jamaah/dokumen', $filename, 'public');
                $validated[$field] = $path;
            }
        }
    }

    public function destroy($id)
    {
        $jamaah = $this->service->getById($id);

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

        $jenisPembayarans = JenisPembayaran::query()
            ->where(function ($q) {
                $q->where('nama', 'not like', '%refund%')
                  ->where('nama', 'not like', '%pengembalian%');
            })
            ->orderBy('nama', 'asc')
            ->get();

        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisPembayaran', 'jenisTransaksi'])
            ->where('id_jamaah', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('jamaahs.pembayaran', compact(
            'jamaah',
            'metodePembayarans',
            'jenisPembayarans',
            'transaksis'
        ));
    }

    public function bayar(Request $request, $id)
    {
        $jumlahBayarRaw = preg_replace('/[^\d]/', '', $request->input('jumlah_bayar'));
        $request->merge(['jumlah_bayar' => (int) $jumlahBayarRaw]);

        $validated = $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayarans,id_metode',
            'id_jenis_pembayaran'  => 'required|exists:jenis_pembayarans,id_jenis_pembayaran',
            'tanggal_transaksi'    => 'required|date',
            'jumlah_bayar'         => 'required|integer|min:1',
            'bukti_pembayaran'     => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'keterangan'           => 'nullable|string',
        ]);

        $jamaah          = $this->service->getById($id);
        $totalTagihan    = $jamaah->total_tagihan_setelah_diskon;
        $totalDibayar    = $jamaah->total_dibayar;
        $jumlahBayar     = $validated['jumlah_bayar'];
        $jenisPembayaran = JenisPembayaran::find($validated['id_jenis_pembayaran']);
        $kode            = $jenisPembayaran->kode;

        $sisaSaatIni = max(0, $totalTagihan - $totalDibayar);

        if ($kode === 'LUNAS' && $jumlahBayar < $sisaSaatIni) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar untuk pelunasan harus sebesar sisa tagihan: Rp ' . number_format($sisaSaatIni, 0, ',', '.'));
        }

        if ($kode === 'REFUND') {
            return redirect()->back()
                ->with('error', 'Refund tidak bisa dilakukan lewat form pembayaran. Gunakan tombol "Proses Refund".');
        }

        if ($jumlahBayar > $sisaSaatIni) {
            return redirect()->back()->with('error', 'Jumlah bayar melebihi sisa tagihan!');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_bukti_' . $jamaah->id_jamaah . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('jamaah/bukti-pembayaran', $filename, 'public');
        }

        TransaksiPembayaran::create([
            'id_jamaah'             => $id,
            'id_metode_pembayaran'  => $validated['id_metode_pembayaran'],
            'id_jenis_transaksi'    => null,
            'id_jenis_pembayaran'   => $jenisPembayaran->id_jenis_pembayaran,
            'tanggal_transaksi'     => $validated['tanggal_transaksi'],
            'jumlah_bayar'          => $jumlahBayar,
            'jenis_mutasi'          => 'masuk',
            'bukti_pembayaran'      => $buktiPath,
            'keterangan'            => $validated['keterangan'],
            'created_by'            => Auth::user()->name ?? 'System',
        ]);

        $totalDibayarBaru = $totalDibayar + $jumlahBayar;
        $sisaTagihanBaru  = max(0, $totalTagihan - $totalDibayarBaru);
        $totalRefund      = (int) ($jamaah->total_refund ?? 0);
        $kelebihanBaru    = max(0, $totalDibayarBaru - $totalTagihan);

        if ($sisaTagihanBaru <= 0) {
            $status = 'Lunas';
        } else {
            $status = $this->mapJenisPembayaranToStatus($kode);
        }

        $jamaah->update([
            'total_dibayar'         => $totalDibayarBaru,
            'sisa_tagihan'          => $sisaTagihanBaru,
            'status_pembayaran'     => $status,
            'sisa_kelebihan_bayar'  => max(0, $kelebihanBaru - $totalRefund),
        ]);

        if ($jamaah->id_keluarga) {
            $keluargaService = new \App\Services\KeluargaService();
            $keluargaService->recalculateKeluarga($jamaah->id_keluarga);
        }

        return redirect()->route('transaksional.jamaah.pembayaran', $id)
            ->with('success', "Pembayaran {$jenisPembayaran->nama} sebesar Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil!");
    }

    public function prosesRefund(Request $request, $id)
    {
        $jumlahRefundRaw = preg_replace('/[^\d]/', '', $request->input('jumlah_refund'));
        $request->merge(['jumlah_refund' => (int) $jumlahRefundRaw]);

        $validated = $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayarans,id_metode',
            'tanggal_transaksi'    => 'required|date',
            'jumlah_refund'        => 'required|integer|min:1',
            'bukti_pembayaran'     => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'keterangan'           => 'nullable|string',
            'keterangan_refund'    => 'nullable|string',
        ]);

        $jamaah = $this->service->getById($id);

        if ($validated['jumlah_refund'] > (int) $jamaah->sisa_kelebihan_bayar) {
            return redirect()->back()
                ->with('error', 'Jumlah refund melebihi sisa kelebihan bayar (maks: ' . $jamaah->sisa_kelebihan_bayar_formatted . ').');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_refund_' . $jamaah->id_jamaah . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('jamaah/bukti-refund', $filename, 'public');
        }

        try {
            $validated['bukti_pembayaran'] = $buktiPath;
            $this->service->prosesRefund($id, $validated);

            return redirect()->route('transaksional.jamaah.pembayaran', $id)
                ->with('success', 'Refund sebesar Rp ' . number_format($validated['jumlah_refund'], 0, ',', '.') . ' berhasil diproses!');
        } catch (\Exception $e) {
            if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses refund: ' . $e->getMessage());
        }
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
        $isRefund = ($transaksi->jenis_mutasi ?? 'masuk') === 'keluar';

        if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
        }

        $transaksi->delete();

        $jamaah = $this->service->getById($jamaahId);
        $totalTagihan = $jamaah->total_tagihan_setelah_diskon;

        $totalDibayarBaru = TransaksiPembayaran::where('id_jamaah', $jamaahId)
            ->where('jenis_mutasi', 'masuk')
            ->sum('jumlah_bayar');
        $totalRefundBaru  = TransaksiPembayaran::where('id_jamaah', $jamaahId)
            ->where('jenis_mutasi', 'keluar')
            ->sum('jumlah_bayar');

        $sisaTagihanBaru = max(0, $totalTagihan - $totalDibayarBaru);
        $kelebihanBaru   = max(0, $totalDibayarBaru - $totalTagihan);

        $lastTransaksi = TransaksiPembayaran::with('jenisPembayaran')
            ->where('id_jamaah', $jamaahId)
            ->where('jenis_mutasi', 'masuk')
            ->orderBy('created_at', 'desc')
            ->first();

        $lastKode = $lastTransaksi->jenisPembayaran->kode ?? null;

        if ($sisaTagihanBaru <= 0) {
            $status = 'Lunas';
        } elseif ($totalDibayarBaru == 0) {
            $status = 'Belum Bayar';
        } else {
            $status = $this->mapJenisPembayaranToStatus($lastKode);
        }

        $jamaah->update([
            'total_dibayar'          => $totalDibayarBaru,
            'sisa_tagihan'           => $sisaTagihanBaru,
            'status_pembayaran'      => $status,
            'total_refund'           => $totalRefundBaru,
            'sisa_kelebihan_bayar'   => max(0, $kelebihanBaru - $totalRefundBaru),
        ]);

        if ($jamaah->id_keluarga) {
            $keluargaService = new \App\Services\KeluargaService();
            $keluargaService->recalculateKeluarga($jamaah->id_keluarga);
        }

        $msg = $isRefund ? 'Transaksi refund berhasil dihapus!' : 'Transaksi pembayaran berhasil dihapus!';

        return redirect()->route('transaksional.jamaah.pembayaran', $jamaahId)
            ->with('success', $msg);
    }

    private function mapJenisPembayaranToStatus(?string $kode): string
    {
        return match (strtoupper(trim((string) $kode))) {
            'DP'       => 'DP',
            'ANGSURAN' => 'Setoran',
            'LUNAS'    => 'Lunas',
            default    => 'Setoran',
        };
    }

    public function cetakPdfRiwayat($id)
    {
        set_time_limit(300);

        try {
            $jamaah = $this->service->getByIdWithRelations($id);

            $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisPembayaran', 'jenisTransaksi'])
                ->where('id_jamaah', $id)
                ->orderBy('created_at', 'desc')
                ->limit(100)
                ->get()
                ->reverse();

            foreach ($transaksis as $transaksi) {
                if ($transaksi->bukti_pembayaran) {
                    $fullPath = storage_path('app/public/' . $transaksi->bukti_pembayaran);
                    if (file_exists($fullPath)) {
                        $fileSize = filesize($fullPath);
                        if ($fileSize < 500000) {
                            $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                            $mimeType = mime_content_type($fullPath);
                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'])) {
                                $imageData = base64_encode(file_get_contents($fullPath));
                                $transaksi->bukti_base64 = 'data:' . $mimeType . ';base64,' . $imageData;
                                $transaksi->bukti_exists = true;
                            } else {
                                $transaksi->bukti_exists = true;
                                $transaksi->bukti_base64 = null;
                            }
                            $transaksi->bukti_extension = $extension;
                            $transaksi->bukti_name = basename($transaksi->bukti_pembayaran);
                        } else {
                            $transaksi->bukti_exists = true;
                            $transaksi->bukti_base64 = null;
                            $transaksi->bukti_extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                            $transaksi->bukti_name = basename($transaksi->bukti_pembayaran);
                        }
                    } else {
                        $transaksi->bukti_exists = false;
                        $transaksi->bukti_base64 = null;
                    }
                } else {
                    $transaksi->bukti_exists = false;
                    $transaksi->bukti_base64 = null;
                }
            }

            $ttdData = $this->loadDokumenTTD();

            $data = [
                'jamaah'          => $jamaah,
                'transaksis'      => $transaksis,
                'total_transaksi' => $transaksis->sum('jumlah_bayar'),
                'tanggal_cetak'   => now()->format('d/m/Y H:i:s'),
                'dicetak_oleh'    => Auth::user()->name ?? 'System',
                'logoBase64'      => $this->loadDokumenBase64('logo'),
                'ttdBase64'       => $ttdData['base64'],
                'ttdNama'         => $ttdData['nama'],
                'ttdJabatan'      => $ttdData['jabatan'],
                'capBase64'       => $this->loadDokumenBase64('cap'),
            ];

            $pdf = Pdf::loadView('jamaahs.pdf-riwayat', $data);
            $pdf->setPaper('A4', 'portrait');

            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'dpi' => 96,
                'isFontSubsettingEnabled' => false,
                'isJavascriptEnabled' => false,
            ]);

            return $pdf->download('Riwayat_Pembayaran_' . $jamaah->nama_lengkap . '_' . date('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    private function loadDokumenBase64($jenis)
    {
        $dokumen = \App\Models\DokumenPerusahaan::where('jenis', $jenis)->first();

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
        $dokumen = \App\Models\DokumenPerusahaan::where('jenis', 'ttd')->first();

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
                'flyer' => $harga->flyer_url,
                'bulan_formatted' => $harga->bulan_formatted,
                'tahun' => $harga->tahun,
                'kuota' => $harga->kuota,
                'kuota_formatted' => $harga->kuota_formatted,
                'kuota_terpakai' => $harga->terpakai,
                'kuota_sisa' => $harga->sisa_kuota,
                'is_full' => $harga->is_full,
            ]);
        }

        $hargaDefault = $produk->hargaBulanan()->active()->first();
        if ($hargaDefault) {
            return response()->json([
                'success' => true,
                'harga' => $hargaDefault->harga,
                'harga_formatted' => $hargaDefault->harga_formatted,
                'flyer' => $hargaDefault->flyer_url,
                'bulan_formatted' => $hargaDefault->bulan_formatted,
                'tahun' => $hargaDefault->tahun,
                'kuota' => $hargaDefault->kuota,
                'kuota_formatted' => $hargaDefault->kuota_formatted,
                'kuota_terpakai' => $hargaDefault->terpakai,
                'kuota_sisa' => $hargaDefault->sisa_kuota,
                'is_full' => $hargaDefault->is_full,
                'warning' => 'Harga untuk bulan/tahun yang dipilih tidak tersedia, menggunakan harga default'
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Tidak ada harga yang tersedia untuk produk ini'
        ], 404);
    }

    public function getKeberangkatanByProduk(Request $request)
    {
        $request->validate([
            'produk_paket' => 'required|string|exists:produk_pakets,nama_produk',
            'exclude_jamaah_id' => 'nullable|integer',
        ]);

        $data = $this->service->getKeberangkatanByProduk(
            $request->produk_paket,
            $request->exclude_jamaah_id
        );

        return response()->json([
            'success' => true,
            'keberangkatan' => $data['keberangkatan'],
            'harga_bulanan' => $data['harga_bulanan'],
        ]);
    }

    public function checkKuota(Request $request)
    {
        $request->validate([
            'id_produk_harga_bulanan' => 'required|exists:produk_harga_bulanan,id',
            'exclude_jamaah_id' => 'nullable|integer',
        ]);

        $harga = ProdukHargaBulanan::find($request->id_produk_harga_bulanan);
        if (!$harga) {
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan']);
        }

        $query = DB::table('jamaah_keberangkatans')
            ->where('id_produk_harga_bulanan', $harga->id);

        if ($request->exclude_jamaah_id) {
            $query->where('id_jamaah', '!=', $request->exclude_jamaah_id);
        }

        $terpakai = $query->count();
        $sisa = $harga->kuota > 0 ? max(0, $harga->kuota - $terpakai) : -1;
        $isFull = $harga->kuota > 0 && $terpakai >= $harga->kuota;

        return response()->json([
            'success' => true,
            'kuota' => $harga->kuota,
            'terpakai' => $terpakai,
            'sisa' => $sisa,
            'is_full' => $isFull,
            'is_unlimited' => $harga->kuota <= 0,
        ]);
    }

    public function getKamarsByHotel($idHotel)
    {
        $hotel = Hotel::findOrFail($idHotel);
        $kamars = Kamar::where('id_hotel', $idHotel)->orderBy('tipe_kamar')->get();

        if ($kamars->isEmpty()) {
            return response()->json([
                'html' => '<p class="text-sm text-gray-400 col-span-2 text-center py-3">Hotel ini belum memiliki data kamar.</p>',
                'count' => 0,
                'hotel' => [
                    'nama' => $hotel->nama_hotel,
                    'kota' => $hotel->kota,
                ],
            ]);
        }

        $html = '';
        foreach ($kamars as $kamar) {
            $kamarId = $kamar->id_kamar ?? $kamar->id;
            $tipeKamar = e($kamar->tipe_kamar);
            $harga = (int) ($kamar->harga ?? 0);
            $hargaFormatted = 'Rp ' . number_format($harga, 0, ',', '.');

            $html .= '<div class="kamar-item bg-white border border-gray-200 rounded-lg p-2">'
                . '<label class="flex items-start gap-2 cursor-pointer">'
                . '<input type="checkbox" '
                . 'class="kamar-checkbox w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500 mt-0.5" '
                . 'value="' . $kamarId . '" '
                . 'data-tipe="' . $tipeKamar . '" '
                . 'data-harga="' . $harga . '">'
                . '<div class="flex-1">'
                . '<div class="flex items-center justify-between">'
                . '<p class="text-xs font-medium text-gray-700">' . $tipeKamar . '</p>'
                . '<p class="text-xs font-bold text-orange-600">' . $hargaFormatted . '</p>'
                . '</div>'
                . '<div class="grid grid-cols-3 gap-1 mt-1">'
                . '<div>'
                . '<label class="block text-[10px] text-gray-500">Kamar</label>'
                . '<input type="number" '
                . 'class="kamar-jumlah w-full px-1.5 py-1 border border-gray-200 rounded text-xs disabled:opacity-50 disabled:bg-gray-50" '
                . 'value="1" min="1" disabled>'
                . '</div>'
                . '<div>'
                . '<label class="block text-[10px] text-gray-500">Harga/Malam</label>'
                . '<input type="text" '
                . 'class="kamar-harga w-full px-1.5 py-1 border border-gray-200 rounded text-xs disabled:opacity-50 disabled:bg-gray-50" '
                . 'value="' . $hargaFormatted . '" '
                . 'oninput="formatRupiahKamar(this)" '
                . 'onblur="formatRupiahKamar(this)" disabled>'
                . '</div>'
                . '<div>'
                . '<label class="block text-[10px] text-gray-500">Malam</label>'
                . '<input type="number" '
                . 'class="kamar-durasi w-full px-1.5 py-1 border border-gray-200 rounded text-xs disabled:opacity-50 disabled:bg-gray-50" '
                . 'value="1" min="1" disabled>'
                . '</div>'
                . '</div>'
                . '</div>'
                . '</label>'
                . '</div>';
        }

        return response()->json([
            'html' => $html,
            'count' => $kamars->count(),
            'hotel' => [
                'nama' => $hotel->nama_hotel,
                'kota' => $hotel->kota,
            ],
        ]);
    }

    public function cetakPdfRekomendasiPassport($id)
    {
        set_time_limit(300);

        try {
            $jamaah = $this->service->getByIdWithRelations($id);

            $keberangkatan = $jamaah->keberangkatan->sortBy([
                'pivot.tahun_keberangkatan',
                'pivot.bulan_keberangkatan',
            ])->first();

            $bulanNama = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $bulanKeberangkatan = '-';
            $tahunKeberangkatan = '-';
            if ($keberangkatan) {
                $bulanKeberangkatan = $bulanNama[$keberangkatan->pivot->bulan_keberangkatan] ?? '-';
                $tahunKeberangkatan = $keberangkatan->pivot->tahun_keberangkatan ?? '-';
            } elseif ($jamaah->bulan_keberangkatan && $jamaah->tahun_keberangkatan) {
                $bulanKeberangkatan = $bulanNama[$jamaah->bulan_keberangkatan] ?? '-';
                $tahunKeberangkatan = $jamaah->tahun_keberangkatan;
            }

            $headerBase64 = $this->loadFileBase64(public_path('assets/header.png'));
            $footer1Base64 = $this->loadFileBase64(public_path('assets/footer1.png'));
            $footer2Base64 = $this->loadFileBase64(public_path('assets/footer2.png'));

            $data = [
                'jamaah'              => $jamaah,
                'bulanKeberangkatan'  => $bulanKeberangkatan,
                'tahunKeberangkatan'  => $tahunKeberangkatan,
                'tanggal_cetak'       => now()->format('d/m/Y'),
                'dicetak_oleh'        => Auth::user()->name ?? 'System',
                'logoBase64'          => $this->loadDokumenBase64('logo'),
                'headerBase64'        => $headerBase64,
                'ttdBase64'           => $this->loadDokumenBase64('ttd'),
                'capBase64'           => $this->loadDokumenBase64('cap'),
                'footer1Base64'       => $footer1Base64,
                'footer2Base64'       => $footer2Base64,
            ];

            $pdf = Pdf::loadView('jamaahs.pdf-rekomendasi-passport', $data);
            $pdf->setPaper('A4', 'portrait');

            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'dpi' => 96,
                'isFontSubsettingEnabled' => false,
                'isJavascriptEnabled' => false,
            ]);

            return $pdf->download(
                'Surat_Rekomendasi_Passport_' . str_replace(' ', '_', $jamaah->nama_lengkap)
                . '_' . date('Ymd_His') . '.pdf'
            );
        } catch (\Exception $e) {
            \Log::error('PDF Rekomendasi Passport Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

    private function loadFileBase64($path)
    {
        if (!file_exists($path)) {
            \Log::warning('File not found: ' . $path);
            return null;
        }
        if (filesize($path) > 2000000) {
            \Log::warning('File too large: ' . $path);
            return null;
        }
        $mime = mime_content_type($path);
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}
