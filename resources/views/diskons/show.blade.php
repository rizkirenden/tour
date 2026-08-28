@extends('layouts.app')

@section('title', 'Detail Diskon - Arrum Tour')
@section('page-title', 'Detail Diskon')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.diskon.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.diskon.index') }}" class="text-gray-500 hover:text-yellow-600">Diskon</a>
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
                    <h5 class="text-sm font-semibold text-gray-700">Detail Diskon</h5>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap diskon</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('master.diskon.riwayat', $diskon->id_diskon) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-history mr-2"></i> Riwayat Reset
                    </a>
                    <button onclick="openResetModal()"
                        class="inline-flex items-center px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-sync-alt mr-2"></i> Reset Kuota
                    </button>
                    <a href="{{ route('master.diskon.edit', $diskon->id_diskon) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('master.diskon.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div
                    class="bg-gradient-to-r from-yellow-50 to-yellow-100/50 rounded-xl p-6 mb-6 border border-yellow-200/50">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-bold text-gray-800">{{ $diskon->nama_diskon }}</h2>
                                <span class="px-3 py-1 bg-purple-500 text-white text-xs font-medium rounded-full">
                                    Reset ke-{{ $diskon->reset_count }}
                                </span>
                            </div>
                            <p class="text-gray-500 text-sm mt-1">
                                <i class="fas fa-tag mr-1"></i> {{ $diskon->berlaku_untuk_produk ?? 'Semua Produk' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-yellow-600">{{ $diskon->nilai_diskon_formatted }}</p>
                            <p class="text-sm text-gray-500">Nilai Diskon</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-yellow-500 mr-2"></i> Informasi Diskon
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Nama Diskon</dt>
                                <dd class="font-medium text-gray-700">{{ $diskon->nama_diskon }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Nilai Diskon</dt>
                                <dd class="font-medium text-yellow-600">{{ $diskon->nilai_diskon_formatted }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Berlaku Untuk</dt>
                                <dd class="font-medium text-gray-700">{{ $diskon->berlaku_untuk_produk ?? 'Semua Produk' }}
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Total Reset</dt>
                                <dd class="font-medium text-purple-600">{{ $diskon->reset_count }} kali</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-layer-group text-yellow-500 mr-2"></i> Kuota
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Kuota</dt>
                                <dd class="font-medium text-gray-700">{{ $diskon->kuota ?? 'Unlimited' }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Sudah Digunakan</dt>
                                <dd class="font-medium text-gray-700">{{ $diskon->sudah_digunakan }}</dd>
                            </div>
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Sisa Kuota</dt>
                                <dd>
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $diskon->status_kuota_color }}">
                                        {{ $diskon->sisa_kuota }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Status Kuota</dt>
                                <dd>
                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $diskon->status_kuota_color }}">
                                        {{ $diskon->status_kuota }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 md:col-span-2">
                        <h6 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-clock text-yellow-500 mr-2"></i> Informasi Sistem
                        </h6>
                        <dl class="space-y-3">
                            <div class="flex justify-between text-sm border-b border-gray-100 pb-2">
                                <dt class="text-gray-500">Dibuat pada</dt>
                                <dd class="font-medium text-gray-700">{{ $diskon->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">Terakhir diupdate</dt>
                                <dd class="font-medium text-gray-700">{{ $diskon->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-end gap-3">
                    <button type="button" onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                    <a href="{{ route('master.diskon.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('master.diskon.edit', $diskon->id_diskon) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium shadow-sm hover:shadow">
                        <i class="fas fa-edit mr-2"></i> Edit Diskon
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reset -->
    <div id="resetModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h5 class="text-lg font-semibold text-gray-800">Reset Kuota Diskon</h5>
                <button onclick="closeResetModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('master.diskon.reset', $diskon->id_diskon) }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                        <p class="text-sm text-yellow-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Data sebelum reset akan disimpan di riwayat reset.
                        </p>
                        <div class="mt-2 text-xs text-gray-500">
                            <p>Nama: <strong>{{ $diskon->nama_diskon }}</strong></p>
                            <p>Kuota saat ini: <strong>{{ $diskon->kuota ?? 'Unlimited' }}</strong></p>
                            <p>Sudah digunakan: <strong>{{ $diskon->sudah_digunakan }}</strong></p>
                            <p>Reset ke-: <strong>{{ ($diskon->reset_count ?? 0) + 1 }}</strong></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kuota Baru</label>
                        <input type="number" name="kuota_baru" id="kuota_baru"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Kosongkan untuk unlimited" min="1">
                        <p class="text-xs text-gray-400 mt-1">Biarkan kosong untuk unlimited</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Reset</label>
                        <input type="text" name="catatan" id="catatan"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Alasan reset (opsional)">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeResetModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition">
                            <i class="fas fa-sync-alt mr-2"></i> Reset Kuota
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openResetModal() {
                document.getElementById('resetModal').classList.remove('hidden');
            }

            function closeResetModal() {
                document.getElementById('resetModal').classList.add('hidden');
            }

            // Close modal on click outside
            document.getElementById('resetModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        </script>
    @endpush
@endsection
