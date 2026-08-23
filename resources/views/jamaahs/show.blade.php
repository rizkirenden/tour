@extends('layouts.app')

@section('title', 'Detail Jamaah - Arrum Tour')
@section('page-title', 'Detail Jamaah')

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
        <span class="text-gray-500 font-medium">Detail</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Detail Jamaah</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap jamaah</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('transaksional.jamaah.edit', $jamaah->id_jamaah) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('transaksional.jamaah.pembayaran', $jamaah->id_jamaah) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-money-bill-wave mr-2"></i> Bayar
                    </a>
                    <a href="{{ route('transaksional.jamaah.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <!-- Header -->
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
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-yellow-600">
                                {{ $jamaah->total_tagihan_setelah_diskon_formatted }}</p>
                            <p class="text-sm text-gray-500">Total Tagihan</p>
                            <p class="text-sm text-green-600">{{ $jamaah->total_dibayar_formatted }} dibayar</p>
                        </div>
                    </div>
                </div>

                <!-- Grid Detail -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Informasi Pribadi -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-user text-yellow-500 mr-2"></i> Informasi Pribadi
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Nama Lengkap</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->nama_lengkap }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Jenis Kelamin</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->jenis_kelamin_label }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Tempat, Tanggal Lahir</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->tempat_lahir ?? '-' }},
                                    {{ $jamaah->tanggal_lahir_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Nomor Paspor</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->nomor_paspor ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Telepon</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->telepon ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Alamat</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->alamat ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Informasi Keberangkatan -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-plane text-yellow-500 mr-2"></i> Informasi Keberangkatan
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">ID Keberangkatan</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->id_keberangkatan }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Produk Paket</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->produk_paket }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Jenis Pendampingan</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->jenis_pendampingan ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Kota Asal</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->kota_asal ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Pulau</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->pulau ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Bandara Keberangkatan</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->bandara_keberangkatan ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Bulan/Tahun Keberangkatan</dt>
                                <dd class="font-medium text-gray-700">
                                    @if ($jamaah->bulan_keberangkatan)
                                        {{ date('F', mktime(0, 0, 0, $jamaah->bulan_keberangkatan, 1)) }}
                                        {{ $jamaah->tahun_keberangkatan }}
                                    @else
                                        -
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Agent & Fee -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-handshake text-yellow-500 mr-2"></i> Agent & Fee
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Agent</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->agent ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Fee Agent</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->fee_agent_formatted }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Keuangan -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-money-bill-wave text-yellow-500 mr-2"></i> Keuangan
                        </h6>
                        <!-- Di bagian Keuangan -->
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Status Pembayaran</dt>
                                <dd class="font-medium text-gray-700">{!! $jamaah->status_pembayaran_badge !!}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Total Tagihan</dt>
                                <dd class="font-medium text-gray-700">
                                    {{ $jamaah->total_tagihan_setelah_diskon_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Total Dibayar</dt>
                                <dd class="font-medium text-green-600">{{ $jamaah->total_dibayar_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Sisa Tagihan</dt>
                                <dd class="font-medium text-red-600">{{ $jamaah->sisa_tagihan_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Diskon</dt>
                                <dd class="font-medium text-gray-700">
                                    @if ($jamaah->diskon)
                                        {{ $jamaah->diskon->nama_diskon }}
                                        <span
                                            class="text-yellow-600">(-{{ $jamaah->diskon->nilai_diskon_formatted }})</span>
                                    @else
                                        {{ $jamaah->nilai_diskon_formatted ?? 'Rp 0' }}
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Keterangan Diskon</dt>
                                <dd class="font-medium text-gray-700">{{ $jamaah->keterangan_diskon ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Ringkasan Tagihan -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-file-invoice text-yellow-500 mr-2"></i> Ringkasan Tagihan
                        </h6>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Tiket Domestik</p>
                                <p class="text-sm font-bold text-gray-800">{{ $jamaah->total_tiket_domestik_formatted }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Tiket International</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $jamaah->total_tiket_international_formatted }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Fee Agent</p>
                                <p class="text-sm font-bold text-gray-800">{{ $jamaah->fee_agent_formatted }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                <p class="text-xs text-gray-500">Diskon ({{ $jamaah->persen_diskon ?? 0 }}%)</p>
                                <p class="text-sm font-bold text-red-600">{{ $jamaah->total_diskon_formatted }}</p>
                            </div>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 border-t border-gray-200 pt-3">
                            <span class="font-medium">Total Sebelum Diskon:</span>
                            <span
                                class="font-bold text-gray-700">{{ $jamaah->total_tagihan_sebelum_diskon_formatted }}</span>
                            <span class="ml-4">→</span>
                            <span class="font-medium">Diskon:</span>
                            <span class="font-bold text-red-600">{{ $jamaah->total_diskon_formatted }}</span>
                            <span class="ml-4">→</span>
                            <span class="font-medium">Total Setelah Diskon:</span>
                            <span
                                class="font-bold text-yellow-600">{{ $jamaah->total_tagihan_setelah_diskon_formatted }}</span>
                        </div>
                    </div>

                    <!-- Foto Dokumen -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-images text-yellow-500 mr-2"></i> Dokumen
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach (['ktp' => 'KTP', 'vaksin' => 'Vaksin', 'visa' => 'Visa'] as $field => $label)
                                <div class="bg-white rounded-lg p-3 border border-gray-200 text-center">
                                    <p class="text-xs text-gray-500 mb-2">Foto {{ $label }}</p>
                                    @php $url = 'foto_' . $field . '_url'; @endphp
                                    @if ($jamaah->$url)
                                        <a href="{{ $jamaah->$url }}" target="_blank" class="inline-block">
                                            <img src="{{ $jamaah->$url }}" alt="Foto {{ $label }}"
                                                class="w-32 h-32 object-cover rounded-lg mx-auto border border-gray-200 hover:shadow-lg transition">
                                        </a>
                                        <p class="text-xs text-gray-400 mt-2">
                                            <a href="{{ $jamaah->$url }}" target="_blank"
                                                class="text-blue-500 hover:text-blue-700">Lihat Full</a>
                                        </p>
                                    @else
                                        <div
                                            class="w-32 h-32 bg-gray-100 rounded-lg mx-auto flex items-center justify-center border border-gray-200">
                                            <i
                                                class="fas {{ $field == 'ktp' ? 'fa-user-circle' : ($field == 'vaksin' ? 'fa-syringe' : 'fa-passport') }} text-4xl text-gray-300"></i>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-2">Tidak ada foto</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Catatan -->
                    @if ($jamaah->catatan_tambahan)
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                            <h6 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-align-left text-yellow-500 mr-2"></i> Catatan Tambahan
                            </h6>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $jamaah->catatan_tambahan }}</p>
                        </div>
                    @endif

                    <!-- Informasi Sistem -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 lg:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Dibuat Pada</dt>
                                <dd class="font-medium text-gray-700">
                                    {{ $jamaah->created_at ? $jamaah->created_at->format('d M Y H:i') : '-' }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir Diperbarui</dt>
                                <dd class="font-medium text-gray-700">
                                    {{ $jamaah->updated_at ? $jamaah->updated_at->format('d M Y H:i') : '-' }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aksi Bawah -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="px-4 py-2 bg-gray-100 rounded-lg">Cetak</button>
                    <a href="{{ route('transaksional.jamaah.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded-lg">Kembali</a>
                    <a href="{{ route('transaksional.jamaah.edit', $jamaah->id_jamaah) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg">Edit</a>
                    <a href="{{ route('transaksional.jamaah.pembayaran', $jamaah->id_jamaah) }}"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg">Bayar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
