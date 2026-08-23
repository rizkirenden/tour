@extends('layouts.app')

@section('title', 'Pembayaran Jamaah - Arrum Tour')
@section('page-title', 'Pembayaran Jamaah')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.jamaah.index') }}" class="text-gray-500 hover:text-yellow-600">Transaksional</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.jamaah.index') }}" class="text-gray-500 hover:text-yellow-600">Jamaah</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Pembayaran</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Form Pembayaran</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Lakukan pembayaran untuk jamaah</p>
                </div>
                <a href="{{ route('transaksional.jamaah.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="p-6">
                <!-- Informasi Jamaah -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $jamaah->nama_lengkap }}</h2>
                                <span class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded-full">
                                    {{ $jamaah->id_keberangkatan }}
                                </span>
                                {!! $jamaah->status_pembayaran_badge !!}
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-box mr-1"></i> Produk: {{ $jamaah->produk_paket }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-map-marker-alt mr-1"></i> Kota Asal: {{ $jamaah->kota_asal ?? '-' }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-phone mr-1"></i> Telepon: {{ $jamaah->telepon ?? '-' }}
                            </p>
                            <p class="text-gray-500 text-sm">
                                <i class="fas fa-passport mr-1"></i> Paspor: {{ $jamaah->nomor_paspor ?? '-' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $jamaah->total_tagihan_setelah_diskon_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Tagihan</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Keuangan -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <p class="text-xs text-gray-500">Total Dibayar</p>
                        <p class="text-xl font-bold text-green-600">{{ $jamaah->total_dibayar_formatted }}</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 border border-red-200 text-center">
                        <p class="text-xs text-gray-500">Sisa Tagihan</p>
                        <p class="text-xl font-bold text-red-600">{{ $jamaah->sisa_tagihan_formatted }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 text-center">
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="text-xl font-bold text-blue-600">{!! $jamaah->status_pembayaran_badge !!}</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-200 text-center">
                        <p class="text-xs text-gray-500">Fee Agent</p>
                        <p class="text-xl font-bold text-purple-600">{{ $jamaah->fee_agent_formatted }}</p>
                    </div>
                </div>

                <!-- Detail Tagihan -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-file-invoice text-yellow-500 mr-2"></i> Detail Tagihan
                        <span class="ml-2 text-xs text-gray-400">(per orang)</span>
                    </h6>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-500">Tiket Domestik</p>
                            <p class="text-sm font-bold text-gray-800">{{ $jamaah->total_tiket_domestik_formatted }}</p>
                            <p class="text-xs text-gray-400">Pergi:
                                {{ $jamaah->harga_tiket_pergi_domestik ? 'Rp ' . number_format($jamaah->harga_tiket_pergi_domestik, 0, ',', '.') : 'Rp 0' }}
                            </p>
                            <p class="text-xs text-gray-400">Pulang:
                                {{ $jamaah->harga_tiket_pulang_domestik ? 'Rp ' . number_format($jamaah->harga_tiket_pulang_domestik, 0, ',', '.') : 'Rp 0' }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-500">Tiket International</p>
                            <p class="text-sm font-bold text-gray-800">{{ $jamaah->total_tiket_international_formatted }}
                            </p>
                            <p class="text-xs text-gray-400">Pergi:
                                {{ $jamaah->harga_tiket_pergi_international ? 'Rp ' . number_format($jamaah->harga_tiket_pergi_international, 0, ',', '.') : 'Rp 0' }}
                            </p>
                            <p class="text-xs text-gray-400">Pulang:
                                {{ $jamaah->harga_tiket_pulang_international ? 'Rp ' . number_format($jamaah->harga_tiket_pulang_international, 0, ',', '.') : 'Rp 0' }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-500">Fee Agent</p>
                            <p class="text-sm font-bold text-gray-800">{{ $jamaah->fee_agent_formatted }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-500">Diskon</p>
                            <p class="text-sm font-bold text-red-600">{{ $jamaah->total_diskon_formatted }}</p>
                            <p class="text-xs text-gray-400">{{ $jamaah->persen_diskon ?? 0 }}%</p>
                            @if ($jamaah->keterangan_diskon)
                                <p class="text-xs text-gray-400">{{ $jamaah->keterangan_diskon }}</p>
                            @endif
                        </div>
                    </div>
                    <div
                        class="mt-3 text-sm text-gray-500 border-t border-gray-200 pt-3 flex flex-wrap items-center justify-between gap-2">
                        <span>
                            <span class="font-medium">Total Sebelum Diskon:</span>
                            <span
                                class="font-bold text-gray-700">{{ $jamaah->total_tagihan_sebelum_diskon_formatted }}</span>
                        </span>
                        <span>
                            <span class="font-medium">Diskon:</span>
                            <span class="font-bold text-red-600">{{ $jamaah->total_diskon_formatted }}</span>
                        </span>
                        <span>
                            <span class="font-medium">Total Setelah Diskon:</span>
                            <span
                                class="font-bold text-yellow-600">{{ $jamaah->total_tagihan_setelah_diskon_formatted }}</span>
                        </span>
                    </div>
                </div>

                <!-- Riwayat Transaksi -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center justify-between">
                        <span>
                            <i class="fas fa-history text-yellow-500 mr-2"></i> Riwayat Pembayaran
                        </span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                            {{ $transaksis->count() }} Transaksi
                        </span>
                    </h6>

                    @if ($transaksis->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Bank</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Jenis</th>
                                        <th
                                            class="px-3 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Bukti</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Keterangan</th>
                                        <th
                                            class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($transaksis as $index => $transaksi)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                            <td class="px-3 py-2 text-gray-600">
                                                {{ $transaksi->tanggal_transaksi_formatted }}</td>
                                            <td class="px-3 py-2">
                                                <span class="text-xs font-medium text-gray-700">
                                                    {{ $transaksi->metodePembayaran->kode_bank ?? '-' }}
                                                </span>
                                                <p class="text-xs text-gray-400">
                                                    {{ $transaksi->metodePembayaran->nama_bank ?? '-' }}</p>
                                            </td>
                                            <td class="px-3 py-2">
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $transaksi->jenisTransaksi->kode == 'DP'
                                ? 'bg-yellow-100 text-yellow-700'
                                : ($transaksi->jenisTransaksi->kode == 'LUNAS'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-blue-100 text-blue-700') }}">
                                                    {{ $transaksi->jenisTransaksi->nama ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right font-medium text-gray-800">
                                                {{ $transaksi->jumlah_bayar_formatted }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($transaksi->bukti_pembayaran_url)
                                                    <a href="{{ $transaksi->bukti_pembayaran_url }}" target="_blank"
                                                        class="text-blue-500 hover:text-blue-700 text-xs">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2 text-gray-500 text-sm max-w-xs truncate">
                                                {{ $transaksi->keterangan ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <!-- Tombol Hapus Transaksi -->
                                                <button onclick="confirmDeleteTransaksi({{ $transaksi->id_transaksi }})"
                                                    class="text-red-500 hover:text-red-700 transition-colors duration-200"
                                                    title="Hapus Transaksi">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                                <form id="delete-transaksi-form-{{ $transaksi->id_transaksi }}"
                                                    action="{{ route('transaksional.jamaah.hapus-transaksi', $transaksi->id_transaksi) }}"
                                                    method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-400">Belum ada riwayat pembayaran</p>
                        </div>
                    @endif
                </div>

                <!-- Form Pembayaran -->
                <form action="{{ route('transaksional.jamaah.bayar', $jamaah->id_jamaah) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <select name="id_metode_pembayaran" id="id_metode_pembayaran"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                                <option value="">-- Pilih Metode --</option>
                                @foreach ($metodePembayarans as $metode)
                                    <option value="{{ $metode->id_metode }}"
                                        {{ old('id_metode_pembayaran') == $metode->id_metode ? 'selected' : '' }}>
                                        {{ $metode->kode_bank }} - {{ $metode->nama_bank }}
                                        ({{ $metode->nomor_rekening }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_metode_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Transaksi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Transaksi <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ($jenisTransaksis as $jenis)
                                    <button type="button" onclick="setJenisTransaksi('{{ $jenis->id_jenis }}')"
                                        class="jenis-btn px-3 py-2 border-2 border-gray-200 rounded-xl hover:border-yellow-500 transition-all duration-200 text-center focus:outline-none text-xs"
                                        data-id="{{ $jenis->id_jenis }}" data-kode="{{ $jenis->kode }}">
                                        <i
                                            class="fas {{ $jenis->kode == 'DP' ? 'fa-hand-holding-usd text-yellow-500' : ($jenis->kode == 'LUNAS' ? 'fa-check-circle text-green-500' : 'fa-coins text-blue-500') }} text-lg block mb-1"></i>
                                        <span class="font-medium">{{ $jenis->nama }}</span>
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="id_jenis_transaksi" id="id_jenis_transaksi"
                                value="{{ old('id_jenis_transaksi') }}">
                            @error('id_jenis_transaksi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="jenisInfo" class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i> Silakan pilih jenis pembayaran
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Tanggal Transaksi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal Transaksi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_transaksi" id="tanggal_transaksi"
                                value="{{ old('tanggal_transaksi', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                            @error('tanggal_transaksi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jumlah Bayar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jumlah Bayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                                    value="{{ old('jumlah_bayar') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-lg"
                                    placeholder="0" min="1" required>
                            </div>
                            @error('jumlah_bayar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-2 flex gap-2 flex-wrap">
                                <button type="button" onclick="setJumlahBayar(25)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">25%</button>
                                <button type="button" onclick="setJumlahBayar(50)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">50%</button>
                                <button type="button" onclick="setJumlahBayar(75)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">75%</button>
                                <button type="button" onclick="setJumlahBayar(100)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">100%</button>
                                <button type="button" onclick="setJumlahBayarSisa()"
                                    class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs hover:bg-yellow-200 transition">Sisa
                                    Tagihan</button>
                            </div>
                            <p id="jumlahInfo" class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Sisa tagihan: <span class="font-bold">{{ $jamaah->sisa_tagihan_formatted }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <!-- Upload Bukti -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_pembayaran" id="bukti_pembayaran"
                                accept="image/*,application/pdf"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100"
                                onchange="previewBukti(this)">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF (Max 2MB)</p>
                            <div id="previewBukti" class="mt-2 hidden">
                                <img id="previewImage" src="#" alt="Preview Bukti"
                                    class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                <p id="previewName" class="text-xs text-gray-500 mt-1"></p>
                            </div>
                            @error('bukti_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                placeholder="Catatan pembayaran (opsional)">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                        <a href="{{ route('transaksional.jamaah.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-check-circle mr-2"></i> Proses Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Preview Bukti Pembayaran
        function previewBukti(input) {
            const preview = document.getElementById('previewBukti');
            const image = document.getElementById('previewImage');
            const name = document.getElementById('previewName');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.classList.remove('hidden');
                    image.src = e.target.result;
                    name.textContent = file.name;
                };

                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }

        function confirmDeleteTransaksi(id) {
            if (confirm(
                    'Yakin ingin menghapus transaksi pembayaran ini?\n\nData yang dihapus:\n- Jumlah pembayaran akan dikembalikan\n- Status pembayaran akan diperbarui otomatis\n- Bukti pembayaran akan dihapus'
                    )) {
                document.getElementById('delete-transaksi-form-' + id).submit();
            }
        }

        // Set Jenis Transaksi
        function setJenisTransaksi(id) {
            document.getElementById('id_jenis_transaksi').value = id;

            // Reset semua button
            document.querySelectorAll('.jenis-btn').forEach(btn => {
                btn.classList.remove('border-yellow-500', 'bg-yellow-50');
                btn.classList.add('border-gray-200');
            });

            // Highlight button yang dipilih
            document.querySelectorAll('.jenis-btn').forEach(btn => {
                if (btn.dataset.id == id) {
                    btn.classList.remove('border-gray-200');
                    btn.classList.add('border-yellow-500', 'bg-yellow-50');
                }
            });

            // Update info
            const btn = document.querySelector(`.jenis-btn[data-id="${id}"]`);
            const kode = btn ? btn.dataset.kode : '';
            const info = document.getElementById('jenisInfo');

            if (kode === 'DP') {
                info.innerHTML =
                    '<i class="fas fa-info-circle mr-1"></i> DP: Pembayaran awal minimal 50% dari total tagihan';
            } else if (kode === 'ANGSURAN') {
                info.innerHTML =
                    '<i class="fas fa-info-circle mr-1"></i> Angsuran: Pembayaran cicilan 50% - 99% dari total tagihan';
            } else if (kode === 'LUNAS') {
                info.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Lunas: Pembayaran 100% dari total tagihan';
            }
        }

        // Set Jumlah Bayar
        function setJumlahBayar(persen) {
            const totalTagihan = {{ $jamaah->total_tagihan_setelah_diskon }};
            const sisaTagihan = {{ $jamaah->sisa_tagihan }};
            let jumlah = Math.round((totalTagihan * persen) / 100);

            if (persen === 100) {
                jumlah = sisaTagihan;
            }

            document.getElementById('jumlah_bayar').value = jumlah;

            document.getElementById('jumlahInfo').innerHTML =
                '<i class="fas fa-info-circle mr-1"></i> ' +
                persen + '% dari total tagihan: <span class="font-bold">Rp ' +
                new Intl.NumberFormat('id-ID').format(jumlah) + '</span>';
        }

        function setJumlahBayarSisa() {
            const sisa = {{ $jamaah->sisa_tagihan }};
            document.getElementById('jumlah_bayar').value = sisa;

            document.getElementById('jumlahInfo').innerHTML =
                '<i class="fas fa-info-circle mr-1"></i> ' +
                'Sisa tagihan: <span class="font-bold">' +
                new Intl.NumberFormat('id-ID').format(sisa) + '</span>';
        }

        // Auto set jika ada old value
        document.addEventListener('DOMContentLoaded', function() {
            const oldJenis = '{{ old('id_jenis_transaksi') }}';
            if (oldJenis) {
                setJenisTransaksi(oldJenis);
            }
        });
    </script>
@endpush
