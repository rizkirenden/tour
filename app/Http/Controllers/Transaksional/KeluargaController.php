<?php
// app/Http/Controllers/Transaksional/KeluargaController.php

namespace App\Http\Controllers\Transaksional;

use App\Http\Controllers\Controller;
use App\Services\KeluargaService;
use App\Models\TransaksiPembayaran;
use App\Models\MetodePembayaran;
use App\Models\JenisPembayaran;
use App\Models\ProdukPaket;
use App\Models\ProdukKeberangkatan;
use App\Models\ProdukHargaBulanan;
use App\Models\Hotel;
use App\Models\Kamar;
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
        $filters = $request->only(['search', 'status_pembayaran', 'has_kelebihan']);
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
        $hotelOptions = Hotel::orderBy('kota')->orderBy('nama_hotel')->get();

        return view('keluargas.create', compact(
            'produkPakets',
            'kotaAsals',
            'hubunganOptions',
            'diskons',
            'hotelOptions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_keluarga' => 'required|string|max:100',
            'produk_paket' => 'required|string|max:100|exists:produk_pakets,nama_produk',
            'id_diskon' => 'nullable|exists:diskons,id_diskon',
            'agent' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',

            // ✅ NEW: keberangkatan_data (konsisten dengan Jamaah)
            'keberangkatan_data' => 'nullable|array',
            'keberangkatan_data.*.id_produk_keberangkatan' => 'required_with:keberangkatan_data|exists:produk_keberangkatans,id',
            'keberangkatan_data.*.id_produk_harga_bulanan' => 'nullable|exists:produk_harga_bulanan,id',
            'keberangkatan_data.*.bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'keberangkatan_data.*.tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keberangkatan_data.*.bulan_harga' => 'nullable|integer|min:1|max:12',
            'keberangkatan_data.*.tahun_harga' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keberangkatan_data.*.harga' => 'nullable|integer|min:0',

            // Field lama (backward compat)
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
            'jamaahs.*.status_kawin' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'jamaahs.*.wa' => 'required|string|max:20',
            'jamaahs.*.alamat' => 'nullable|string',
            'jamaahs.*.kota_kabupaten' => 'nullable|string|max:100',
            'jamaahs.*.kecamatan' => 'nullable|string|max:100',
            'jamaahs.*.kelurahan' => 'nullable|string|max:100',
            'jamaahs.*.kota_asal' => 'nullable|string|max:50|exists:kota_asals,nama_kota',
            'jamaahs.*.pulau' => 'nullable|string|max:20',
            'jamaahs.*.bandara_keberangkatan' => 'nullable|string|max:50',
            'jamaahs.*.nomor_paspor' => 'nullable|string|max:20',
            'jamaahs.*.paspor_terbit' => 'nullable|date',
            'jamaahs.*.paspor_expired' => 'nullable|date',
            'jamaahs.*.paspor_diterbitkan_di' => 'nullable|string|max:100',
            'jamaahs.*.tanggal_lahir' => 'nullable|date',
            'jamaahs.*.tempat_lahir' => 'required|string|max:100',
            'jamaahs.*.file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',

            'jamaahs.*.is_pembuatan_passport' => 'nullable|boolean',
            'jamaahs.*.pendampingan_fee' => 'nullable|integer|min:0',
            'jamaahs.*.pendampingan_fee_petugas' => 'nullable|integer|min:0',

            'upgrade_hotels' => 'nullable|array',
            'upgrade_hotels.*.id_hotel' => 'required_with:upgrade_hotels|exists:hotels,id_hotel',
            'upgrade_hotels.*.id_kamar' => 'nullable|exists:kamars,id_kamar',
            'upgrade_hotels.*.tipe_kamar' => 'nullable|string|max:100',
            'upgrade_hotels.*.jumlah_kamar' => 'nullable|integer|min:1',
            'upgrade_hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'upgrade_hotels.*.harga_per_malam' => 'nullable|integer|min:0',
            'upgrade_hotels.*.catatan' => 'nullable|string',

            'jamaahs.*.upgrade_hotels' => 'nullable|array',
            'jamaahs.*.upgrade_hotels.*.id_hotel' => 'required_with:jamaahs.*.upgrade_hotels|exists:hotels,id_hotel',
            'jamaahs.*.upgrade_hotels.*.id_kamar' => 'nullable|exists:kamars,id_kamar',
            'jamaahs.*.upgrade_hotels.*.tipe_kamar' => 'nullable|string|max:100',
            'jamaahs.*.upgrade_hotels.*.jumlah_kamar' => 'nullable|integer|min:1',
            'jamaahs.*.upgrade_hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'jamaahs.*.upgrade_hotels.*.harga_per_malam' => 'nullable|integer|min:0',
            'jamaahs.*.upgrade_hotels.*.catatan' => 'nullable|string',
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
        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisPembayaran', 'jenisTransaksi', 'jamaah'])
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
        $hotelOptions = Hotel::orderBy('kota')->orderBy('nama_hotel')->get();

        $jamaahData = old('jamaahs');

        if (!$jamaahData) {
            $jamaahData = $keluarga->jamaahs->map(function ($jamaah) {
                return [
                    'id_jamaah' => $jamaah->id_jamaah,
                    'nama_lengkap' => $jamaah->nama_lengkap,
                    'nik' => $jamaah->nik,
                    'nama_ayah' => $jamaah->nama_ayah,
                    'pekerjaan' => $jamaah->pekerjaan,
                    'hubungan_keluarga' => $jamaah->hubungan_keluarga,
                    'is_kepala_keluarga' => $jamaah->is_kepala_keluarga,
                    'jenis_kelamin' => $jamaah->jenis_kelamin,
                    'status_kawin' => $jamaah->status_kawin,
                    'wa' => $jamaah->wa,
                    'alamat' => $jamaah->alamat,
                    'kota_kabupaten' => $jamaah->kota_kabupaten,
                    'kecamatan' => $jamaah->kecamatan,
                    'kelurahan' => $jamaah->kelurahan,
                    'kota_asal' => $jamaah->kota_asal,
                    'pulau' => $jamaah->pulau,
                    'bandara_keberangkatan' => $jamaah->bandara_keberangkatan,
                    'nomor_paspor' => $jamaah->nomor_paspor,
                    'paspor_terbit' => $jamaah->paspor_terbit ? $jamaah->paspor_terbit->format('Y-m-d') : null,
                    'paspor_expired' => $jamaah->paspor_expired ? $jamaah->paspor_expired->format('Y-m-d') : null,
                    'paspor_diterbitkan_di' => $jamaah->paspor_diterbitkan_di,
                    'tempat_lahir' => $jamaah->tempat_lahir,
                    'tanggal_lahir' => $jamaah->tanggal_lahir ? $jamaah->tanggal_lahir->format('Y-m-d') : null,
                    'is_pembuatan_passport' => $jamaah->is_pembuatan_passport,
                    'pendampingan_fee' => $jamaah->pendampingan_fee,
                    'pendampingan_fee_petugas' => $jamaah->pendampingan_fee_petugas,
                    'file_ktp_kk' => $jamaah->file_ktp_kk,
                    'file_vaksin' => $jamaah->file_vaksin,
                    'file_visa' => $jamaah->file_visa,
                    'file_paspor' => $jamaah->file_paspor,
                    'upgrade_hotels' => $jamaah->upgradeHotels->map(function ($u) {
                        return [
                            'id_hotel' => $u->id_hotel,
                            'id_kamar' => $u->id_kamar,
                            'tipe_kamar' => $u->tipe_kamar,
                            'jumlah_kamar' => $u->jumlah_kamar,
                            'durasi_menginap' => $u->durasi_menginap,
                            'harga_per_malam' => $u->harga_per_malam,
                        ];
                    })->toArray(),
                ];
            })->toArray();
        }

        if (empty($jamaahData)) {
            $jamaahData = [[
                'id_jamaah' => '', 'nama_lengkap' => '', 'nik' => '', 'nama_ayah' => '',
                'pekerjaan' => '', 'hubungan_keluarga' => '', 'is_kepala_keluarga' => true,
                'jenis_kelamin' => '', 'status_kawin' => '',
                'wa' => '',
                'alamat' => '', 'kota_kabupaten' => '', 'kecamatan' => '', 'kelurahan' => '',
                'kota_asal' => '', 'pulau' => '', 'bandara_keberangkatan' => '',
                'nomor_paspor' => '', 'paspor_terbit' => null, 'paspor_expired' => null,
                'paspor_diterbitkan_di' => '', 'tempat_lahir' => '', 'tanggal_lahir' => '',
                'is_pembuatan_passport' => false, 'pendampingan_fee' => 0,
                'pendampingan_fee_petugas' => 0, 'file_ktp_kk' => null, 'file_vaksin' => null,
                'file_visa' => null, 'file_paspor' => null, 'upgrade_hotels' => [],
            ]];
        }

        return view('keluargas.edit', compact(
            'keluarga',
            'produkPakets',
            'kotaAsals',
            'hubunganOptions',
            'diskons',
            'hotelOptions',
            'jamaahData'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_keluarga' => 'required|string|max:100',
            'produk_paket' => 'required|string|max:100|exists:produk_pakets,nama_produk',
            'id_diskon' => 'nullable|exists:diskons,id_diskon',
            'agent' => 'nullable|string|max:100',
            'fee_agent' => 'nullable|integer|min:0',

            // ✅ NEW: keberangkatan_data (konsisten dengan Jamaah)
            'keberangkatan_data' => 'nullable|array',
            'keberangkatan_data.*.id_produk_keberangkatan' => 'required_with:keberangkatan_data|exists:produk_keberangkatans,id',
            'keberangkatan_data.*.id_produk_harga_bulanan' => 'nullable|exists:produk_harga_bulanan,id',
            'keberangkatan_data.*.bulan_keberangkatan' => 'nullable|integer|min:1|max:12',
            'keberangkatan_data.*.tahun_keberangkatan' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keberangkatan_data.*.bulan_harga' => 'nullable|integer|min:1|max:12',
            'keberangkatan_data.*.tahun_harga' => 'nullable|integer|min:2000|max:' . (date('Y') + 10),
            'keberangkatan_data.*.harga' => 'nullable|integer|min:0',

            // Field lama (backward compat)
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
            'jamaahs.*.status_kawin' => 'nullable|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'jamaahs.*.wa' => 'required|string|max:20',
            'jamaahs.*.alamat' => 'nullable|string',
            'jamaahs.*.kota_kabupaten' => 'nullable|string|max:100',
            'jamaahs.*.kecamatan' => 'nullable|string|max:100',
            'jamaahs.*.kelurahan' => 'nullable|string|max:100',
            'jamaahs.*.kota_asal' => 'nullable|string|max:50|exists:kota_asals,nama_kota',
            'jamaahs.*.pulau' => 'nullable|string|max:20',
            'jamaahs.*.bandara_keberangkatan' => 'nullable|string|max:50',
            'jamaahs.*.nomor_paspor' => 'nullable|string|max:20',
            'jamaahs.*.paspor_terbit' => 'nullable|date',
            'jamaahs.*.paspor_expired' => 'nullable|date',
            'jamaahs.*.paspor_diterbitkan_di' => 'nullable|string|max:100',
            'jamaahs.*.tanggal_lahir' => 'nullable|date',
            'jamaahs.*.tempat_lahir' => 'required|string|max:100',
            'jamaahs.*.file_ktp_kk' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_vaksin' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_visa' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'jamaahs.*.file_paspor' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',

            'jamaahs.*.is_pembuatan_passport' => 'nullable|boolean',
            'jamaahs.*.pendampingan_fee' => 'nullable|integer|min:0',
            'jamaahs.*.pendampingan_fee_petugas' => 'nullable|integer|min:0',

            'upgrade_hotels' => 'nullable|array',
            'upgrade_hotels.*.id_hotel' => 'required_with:upgrade_hotels|exists:hotels,id_hotel',
            'upgrade_hotels.*.id_kamar' => 'nullable|exists:kamars,id_kamar',
            'upgrade_hotels.*.tipe_kamar' => 'nullable|string|max:100',
            'upgrade_hotels.*.jumlah_kamar' => 'nullable|integer|min:1',
            'upgrade_hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'upgrade_hotels.*.harga_per_malam' => 'nullable|integer|min:0',
            'upgrade_hotels.*.catatan' => 'nullable|string',

            'jamaahs.*.upgrade_hotels' => 'nullable|array',
            'jamaahs.*.upgrade_hotels.*.id_hotel' => 'required_with:jamaahs.*.upgrade_hotels|exists:hotels,id_hotel',
            'jamaahs.*.upgrade_hotels.*.id_kamar' => 'nullable|exists:kamars,id_kamar',
            'jamaahs.*.upgrade_hotels.*.tipe_kamar' => 'nullable|string|max:100',
            'jamaahs.*.upgrade_hotels.*.jumlah_kamar' => 'nullable|integer|min:1',
            'jamaahs.*.upgrade_hotels.*.durasi_menginap' => 'nullable|integer|min:1',
            'jamaahs.*.upgrade_hotels.*.harga_per_malam' => 'nullable|integer|min:0',
            'jamaahs.*.upgrade_hotels.*.catatan' => 'nullable|string',
        ]);

        try {
            $keluarga = $this->service->update($id, $validated);

            $msg = "Keluarga '{$keluarga->nama_keluarga}' berhasil diperbarui!";

            if ($keluarga->sisa_kelebihan_bayar > 0) {
                $msg .= " ⚠️ Ada kelebihan bayar: {$keluarga->sisa_kelebihan_bayar_formatted} — silakan proses refund di halaman pembayaran.";
            }

            return redirect()->route('transaksional.keluarga.index')
                ->with('success', $msg);
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

        $jenisPembayarans = JenisPembayaran::query()
            ->where(function ($q) {
                $q->where('nama', 'not like', '%refund%')
                  ->where('nama', 'not like', '%pengembalian%');
            })
            ->orderBy('nama', 'asc')
            ->get();

        return view('keluargas.pembayaran', compact(
            'keluarga',
            'metodePembayarans',
            'jenisPembayarans'
        ));
    }

    public function bayar(Request $request, $id)
    {
        $jumlahRaw = preg_replace('/[^\d]/', '', $request->input('jumlah_bayar'));
        $request->merge(['jumlah_bayar' => (int) $jumlahRaw]);

        $validated = $request->validate([
            'id_metode_pembayaran' => 'required|exists:metode_pembayarans,id_metode',
            'id_jenis_pembayaran'  => 'required|exists:jenis_pembayarans,id_jenis_pembayaran',
            'tanggal_transaksi'    => 'required|date',
            'jumlah_bayar'         => 'required|integer|min:1',
            'bukti_pembayaran'     => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'keterangan'           => 'nullable|string'
        ]);

        $keluarga            = $this->service->getById($id);
        $totalTagihan        = $keluarga->total_tagihan_setelah_diskon;
        $totalDibayarSaatIni = $keluarga->total_dibayar;
        $jumlahBayar         = $validated['jumlah_bayar'];
        $jenisPembayaran     = JenisPembayaran::find($validated['id_jenis_pembayaran']);
        $kode                = $jenisPembayaran->kode;

        $sisaSaatIni = max(0, $totalTagihan - $totalDibayarSaatIni);

        if ($kode === 'LUNAS' && $jumlahBayar < $sisaSaatIni) {
            return redirect()->back()
                ->with('error', 'Jumlah bayar untuk pelunasan harus sebesar sisa tagihan: ' . number_format($sisaSaatIni, 0, ',', '.'));
        }

        if ($kode === 'REFUND') {
            return redirect()->back()
                ->with('error', 'Refund tidak bisa dilakukan lewat form pembayaran. Gunakan tombol "Proses Refund".');
        }

        if ($jumlahBayar > $sisaSaatIni) {
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
        $sisaTagihanBaru  = max(0, $totalTagihan - $totalDibayarBaru);
        $kelebihanBaru    = max(0, $totalDibayarBaru - $totalTagihan);
        $totalRefund      = (int) ($keluarga->total_refund ?? 0);

        if ($sisaTagihanBaru <= 0) {
            $status = 'Lunas';
        } else {
            $status = $this->mapJenisPembayaranToStatus($kode);
        }

        $keluarga->update([
            'total_dibayar'         => $totalDibayarBaru,
            'sisa_tagihan'          => $sisaTagihanBaru,
            'status_pembayaran'     => $status,
            'sisa_kelebihan_bayar'  => max(0, $kelebihanBaru - $totalRefund),
        ]);

        $jamaahs = $keluarga->jamaahs;
        $totalTagihanKeluarga = $keluarga->total_tagihan_setelah_diskon;
        $sisaPembagian = $jumlahBayar;

        foreach ($jamaahs as $index => $jamaah) {
            if ($index === $jamaahs->count() - 1) {
                $bayarPerJamaah = $sisaPembagian;
            } else {
                $proporsi = $totalTagihanKeluarga > 0
                    ? $jamaah->total_tagihan_setelah_diskon / $totalTagihanKeluarga
                    : 1 / max(1, $jamaahs->count());
                $bayarPerJamaah = (int) round($jumlahBayar * $proporsi);
                $sisaPembagian -= $bayarPerJamaah;
            }
            $bayarPerJamaah = max(0, $bayarPerJamaah);

            $totalDibayarJamaah  = $jamaah->total_dibayar + $bayarPerJamaah;
            $sisaTagihanJamaah   = max(0, $jamaah->total_tagihan_setelah_diskon - $totalDibayarJamaah);
            $kelebihanJamaah     = max(0, $totalDibayarJamaah - $jamaah->total_tagihan_setelah_diskon);
            $totalRefundJamaah   = (int) ($jamaah->total_refund ?? 0);

            if ($sisaTagihanJamaah <= 0) {
                $statusJamaah = 'Lunas';
            } else {
                $statusJamaah = $this->mapJenisPembayaranToStatus($kode);
            }

            $jamaah->update([
                'total_dibayar'         => $totalDibayarJamaah,
                'sisa_tagihan'          => $sisaTagihanJamaah,
                'status_pembayaran'     => $statusJamaah,
                'sisa_kelebihan_bayar'  => max(0, $kelebihanJamaah - $totalRefundJamaah),
            ]);

            if ($bayarPerJamaah > 0) {
                TransaksiPembayaran::create([
                    'id_jamaah'             => $jamaah->id_jamaah,
                    'id_metode_pembayaran'  => $validated['id_metode_pembayaran'],
                    'id_jenis_transaksi'    => null,
                    'id_jenis_pembayaran'   => $jenisPembayaran->id_jenis_pembayaran,
                    'tanggal_transaksi'     => $validated['tanggal_transaksi'],
                    'jumlah_bayar'          => $bayarPerJamaah,
                    'jenis_mutasi'          => 'masuk',
                    'bukti_pembayaran'      => $buktiPath,
                    'keterangan'            => ($validated['keterangan'] ?? '') . ' (Dari pembayaran keluarga)',
                    'created_by'            => Auth::user()->name ?? 'System',
                ]);
            }
        }

        return redirect()->route('transaksional.keluarga.show', $id)
            ->with('success', "Pembayaran keluarga sebesar Rp " . number_format($jumlahBayar, 0, ',', '.') . " berhasil dan telah masuk ke riwayat pembayaran jamaah masing-masing!");
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

        $keluarga = $this->service->getById($id);

        if ($validated['jumlah_refund'] > (int) $keluarga->sisa_kelebihan_bayar) {
            return redirect()->back()
                ->with('error', 'Jumlah refund melebihi sisa kelebihan bayar (maks: ' . $keluarga->sisa_kelebihan_bayar_formatted . ').');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_refund_keluarga_' . $keluarga->id_keluarga . '.' . $file->getClientOriginalExtension();
            $buktiPath = $file->storeAs('jamaah/bukti-refund', $filename, 'public');
        }

        try {
            $validated['bukti_pembayaran'] = $buktiPath;
            $this->service->prosesRefund($id, $validated);

            return redirect()->route('transaksional.keluarga.pembayaran', $id)
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

    public function hapusTransaksi($idTransaksi)
    {
        return DB::transaction(function () use ($idTransaksi) {
            $transaksi = TransaksiPembayaran::findOrFail($idTransaksi);
            $isRefund = ($transaksi->jenis_mutasi ?? 'masuk') === 'keluar';

            $jamaah = \App\Models\Jamaah::find($transaksi->id_jamaah);
            $keluarga = $jamaah ? \App\Models\Keluarga::find($jamaah->id_keluarga) : null;

            if ($jamaah) {
                if ($isRefund) {
                    $totalRefundBaru = max(0, (int) $jamaah->total_refund - (int) $transaksi->jumlah_bayar);
                    $sisaKelebihanBaru = max(0, $jamaah->kelebihan_bayar - $totalRefundBaru);
                    $jamaah->update([
                        'total_refund' => $totalRefundBaru,
                        'sisa_kelebihan_bayar' => $sisaKelebihanBaru,
                    ]);
                } else {
                    $totalDibayarBaru = max(0, $jamaah->total_dibayar - $transaksi->jumlah_bayar);
                    $sisaTagihanBaru = max(0, $jamaah->total_tagihan_setelah_diskon - $totalDibayarBaru);
                    $kelebihanBaru = max(0, $totalDibayarBaru - $jamaah->total_tagihan_setelah_diskon);
                    $totalRefundJamaah = (int) ($jamaah->total_refund ?? 0);

                    $lastTransaksi = TransaksiPembayaran::with('jenisPembayaran')
                        ->where('id_jamaah', $jamaah->id_jamaah)
                        ->where('jenis_mutasi', 'masuk')
                        ->where('id_transaksi', '!=', $transaksi->id_transaksi)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    $lastKode = $lastTransaksi->jenisPembayaran->kode ?? null;

                    if ($sisaTagihanBaru <= 0) {
                        $statusJamaah = 'Lunas';
                    } elseif ($totalDibayarBaru == 0) {
                        $statusJamaah = 'Belum Bayar';
                    } else {
                        $statusJamaah = $this->mapJenisPembayaranToStatus($lastKode);
                    }

                    $jamaah->update([
                        'total_dibayar' => $totalDibayarBaru,
                        'sisa_tagihan' => $sisaTagihanBaru,
                        'status_pembayaran' => $statusJamaah,
                        'sisa_kelebihan_bayar' => max(0, $kelebihanBaru - $totalRefundJamaah),
                    ]);
                }
            }

            if ($transaksi->bukti_pembayaran && Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
            }

            $transaksi->delete();

            if ($keluarga) {
                $this->service->recalculateKeluarga($keluarga->id_keluarga);
            }

            $msg = $isRefund ? 'Transaksi refund berhasil dihapus!' : 'Transaksi pembayaran berhasil dihapus dan total tagihan telah diperbarui.';

            return redirect()->route('transaksional.keluarga.show', $keluarga->id_keluarga ?? null)
                ->with('success', $msg);
        });
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

    public function cetakPembayaran($id)
    {
        $keluarga = $this->service->getById($id);

        $jamaahIds = $keluarga->jamaahs->pluck('id_jamaah')->toArray();
        $transaksis = TransaksiPembayaran::with(['metodePembayaran', 'jenisPembayaran', 'jenisTransaksi', 'jamaah'])
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
            'exclude_jamaah_ids' => 'nullable|string',
        ]);

        $excludeIds = [];
        if ($request->filled('exclude_jamaah_ids')) {
            $excludeIds = collect(explode(',', $request->exclude_jamaah_ids))
                ->map(fn($v) => (int) trim($v))
                ->filter(fn($v) => $v > 0)
                ->values()
                ->toArray();
        }

        $data = $this->service->getKeberangkatanByProduk($request->produk_paket, $excludeIds);

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
}
