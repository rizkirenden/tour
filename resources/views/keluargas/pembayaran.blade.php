@extends('layouts.app')

@section('title', 'Pembayaran Keluarga - Arrum Tour')
@section('page-title', 'Pembayaran Keluarga')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.keluarga.show', $keluarga->id_keluarga) }}"
            class="text-gray-500 hover:text-yellow-600">Keluarga</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Form Pembayaran Keluarga</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Lakukan pembayaran untuk seluruh keluarga</p>
                </div>
                <a href="{{ route('transaksional.keluarga.show', $keluarga->id_keluarga) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="p-6">
                <!-- Header -->
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">
                                {{ $keluarga->nama_kepala_keluarga ?? $keluarga->nama_keluarga }}</h2>
                            <span class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded-full">
                                {{ $keluarga->kode_keluarga }}
                            </span>
                            {!! $keluarga->status_pembayaran_badge !!}
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-users mr-1"></i> Anggota: {{ $keluarga->jamaahs->count() }} Orang
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $keluarga->total_tagihan_setelah_diskon_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Tagihan</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Keuangan - 3 Kolom -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200 text-center">
                        <p class="text-xs text-gray-500">Total Dibayar</p>
                        <p class="text-xl font-bold text-green-600">{{ $keluarga->total_dibayar_formatted }}</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 border border-red-200 text-center">
                        <p class="text-xs text-gray-500">Sisa Tagihan</p>
                        <p class="text-xl font-bold text-red-600">{{ $keluarga->sisa_tagihan_formatted }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 text-center">
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="text-xl font-bold text-blue-600">{!! $keluarga->status_pembayaran_badge !!}</p>
                    </div>
                </div>

                <!-- Informasi Pembayaran Keluarga -->
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm text-blue-700 font-medium">Informasi Pembayaran Keluarga</p>
                            <p class="text-xs text-blue-600 mt-1">
                                Pembayaran akan dibagi secara proporsional ke seluruh anggota keluarga berdasarkan tagihan
                                masing-masing.
                                Setiap anggota akan menerima catatan pembayaran dari keluarga.
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
                                <i class="fas fa-check-circle mr-1"></i>
                                Riwayat pembayaran akan muncul di halaman detail setiap jamaah.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Daftar Anggota -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
                    <h6 class="text-sm font-semibold text-gray-700 mb-4">Rincian Tagihan per Anggota</h6>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="px-3 py-2 text-left">#</th>
                                    <th class="px-3 py-2 text-left">Nama</th>
                                    <th class="px-3 py-2 text-right">Tagihan</th>
                                    <th class="px-3 py-2 text-right">Dibayar</th>
                                    <th class="px-3 py-2 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keluarga->jamaahs as $jamaah)
                                    <tr>
                                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2">{{ $jamaah->nama_lengkap }}</td>
                                        <td class="px-3 py-2 text-right">
                                            {{ $jamaah->total_tagihan_setelah_diskon_formatted }}</td>
                                        <td class="px-3 py-2 text-right">{{ $jamaah->total_dibayar_formatted }}</td>
                                        <td class="px-3 py-2 text-center">{!! $jamaah->status_pembayaran_badge !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Pembayaran -->
                <form action="{{ route('transaksional.keluarga.bayar', $keluarga->id_keluarga) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Metode Pembayaran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran *</label>
                            <select name="id_metode_pembayaran"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                required>
                                <option value="">-- Pilih Metode --</option>

                                @php
                                    $bankTransfers = $metodePembayarans->where('jenis_pembayaran', 'bank_transfer');
                                    $cashs = $metodePembayarans->where('jenis_pembayaran', 'cash');
                                    $eWallets = $metodePembayarans->where('jenis_pembayaran', 'e_wallet');
                                @endphp

                                @if ($bankTransfers->count() > 0)
                                    <optgroup label="🏦 Bank Transfer">
                                        @foreach ($bankTransfers as $metode)
                                            <option value="{{ $metode->id_metode }}"
                                                {{ old('id_metode_pembayaran') == $metode->id_metode ? 'selected' : '' }}>
                                                {{ $metode->kode_bank }} - {{ $metode->nama_bank }}
                                                ({{ $metode->nomor_rekening }})
                                                @if ($metode->atas_nama)
                                                    - a/n {{ $metode->atas_nama }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if ($cashs->count() > 0)
                                    <optgroup label="💰 Cash / Tunai">
                                        @foreach ($cashs as $metode)
                                            <option value="{{ $metode->id_metode }}"
                                                {{ old('id_metode_pembayaran') == $metode->id_metode ? 'selected' : '' }}>
                                                Cash / Tunai
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if ($eWallets->count() > 0)
                                    <optgroup label="📱 E-Wallet">
                                        @foreach ($eWallets as $metode)
                                            <option value="{{ $metode->id_metode }}"
                                                {{ old('id_metode_pembayaran') == $metode->id_metode ? 'selected' : '' }}>
                                                {{ $metode->e_wallet_type }} - {{ $metode->nomor_telepon }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>

                        <!-- Jenis Transaksi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Transaksi *</label>
                            <select name="id_jenis_transaksi"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                required>
                                <option value="">-- Pilih --</option>
                                @foreach ($jenisTransaksis as $jenis)
                                    <option value="{{ $jenis->id_jenis }}"
                                        {{ old('id_jenis_transaksi') == $jenis->id_jenis ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Transaksi *</label>
                            <input type="date" name="tanggal_transaksi"
                                value="{{ old('tanggal_transaksi', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah Bayar *</label>
                            <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                placeholder="0" min="1" required>
                            <div class="mt-2 flex gap-2 flex-wrap">
                                <button type="button" onclick="setJumlah(25)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">25%</button>
                                <button type="button" onclick="setJumlah(50)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">50%</button>
                                <button type="button" onclick="setJumlah(75)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">75%</button>
                                <button type="button" onclick="setJumlah(100)"
                                    class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs hover:bg-gray-200 transition">100%</button>
                                <button type="button" onclick="setJumlahSisa()"
                                    class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs hover:bg-yellow-200 transition">Sisa</button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Upload Bukti</label>
                            <input type="file" name="bukti_pembayaran" accept="image/*,application/pdf"
                                class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, PDF (Max 2MB)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                            <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                placeholder="Catatan (opsional)">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t mt-6">
                        <a href="{{ route('transaksional.keluarga.show', $keluarga->id_keluarga) }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">Batal</a>
                        <button type="submit"
                            class="px-6 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition shadow-sm hover:shadow">
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
        const sisaTagihan = {{ $keluarga->sisa_tagihan }};
        const totalTagihan = {{ $keluarga->total_tagihan_setelah_diskon }};

        function setJumlah(persen) {
            let jumlah = Math.round((totalTagihan * persen) / 100);
            if (persen === 100) jumlah = sisaTagihan;
            document.getElementById('jumlah_bayar').value = jumlah;
        }

        function setJumlahSisa() {
            document.getElementById('jumlah_bayar').value = sisaTagihan;
        }
    </script>
@endpush
