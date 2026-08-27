@extends('layouts.app')

@section('title', 'Tambah Metode Pembayaran - Arrum Tour')
@section('page-title', 'Tambah Metode Pembayaran')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.metode-pembayaran.index') }}" class="text-gray-500 hover:text-yellow-600">Master Data</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('master.metode-pembayaran.index') }}" class="text-gray-500 hover:text-yellow-600">Metode
            Pembayaran</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Tambah</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-sm font-semibold text-gray-700">Form Tambah Metode Pembayaran</h5>
            </div>

            <form action="{{ route('master.metode-pembayaran.store') }}" method="POST">
                @csrf

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jenis Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_pembayaran" id="jenis_pembayaran"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            required>
                            <option value="">Pilih Jenis Pembayaran</option>
                            @foreach ($jenisList as $key => $label)
                                <option value="{{ $key }}" {{ old('jenis_pembayaran') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_pembayaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bank Transfer Fields -->
                    <div id="bank-transfer-fields" class="space-y-6 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kode Bank <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="kode_bank" value="{{ old('kode_bank') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm uppercase"
                                    placeholder="Contoh: BCA, MANDIRI, BRI">
                                @error('kode_bank')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Bank <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_bank" value="{{ old('nama_bank') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: Bank Central Asia">
                                @error('nama_bank')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nomor Rekening <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: 1234567890">
                                @error('nomor_rekening')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Atas Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="atas_nama" value="{{ old('atas_nama') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: PT Arrum Tour">
                                @error('atas_nama')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- E-Wallet Fields -->
                    <div id="e-wallet-fields" class="space-y-6 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    E-Wallet Type <span class="text-red-500">*</span>
                                </label>
                                <select name="e_wallet_type"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                                    <option value="">Pilih E-Wallet</option>
                                    <option value="OVO" {{ old('e_wallet_type') == 'OVO' ? 'selected' : '' }}>OVO
                                    </option>
                                    <option value="GoPay" {{ old('e_wallet_type') == 'GoPay' ? 'selected' : '' }}>GoPay
                                    </option>
                                    <option value="DANA" {{ old('e_wallet_type') == 'DANA' ? 'selected' : '' }}>DANA
                                    </option>
                                    <option value="LinkAja" {{ old('e_wallet_type') == 'LinkAja' ? 'selected' : '' }}>
                                        LinkAja</option>
                                    <option value="ShopeePay" {{ old('e_wallet_type') == 'ShopeePay' ? 'selected' : '' }}>
                                        ShopeePay</option>
                                </select>
                                @error('e_wallet_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                    placeholder="Contoh: 081234567890">
                                @error('nomor_telepon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Cash Info -->
                    <div id="cash-info" class="hidden bg-green-50 rounded-xl p-4 border border-green-100">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-green-500 mt-0.5 mr-3"></i>
                            <div>
                                <p class="text-sm text-green-700 font-medium">Metode Pembayaran Tunai</p>
                                <p class="text-sm text-green-600 mt-1">Metode pembayaran tunai akan otomatis ditambahkan
                                    dengan:</p>
                                <ul class="text-xs text-green-600 mt-1 space-y-0.5">
                                    <li>• Kode Bank: CASH</li>
                                    <li>• Nama Bank: Cash / Tunai</li>
                                    <li>• Informasi rekening tidak diperlukan</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select name="is_active"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('master.metode-pembayaran.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jenisSelect = document.getElementById('jenis_pembayaran');
            const bankFields = document.getElementById('bank-transfer-fields');
            const eWalletFields = document.getElementById('e-wallet-fields');
            const cashInfo = document.getElementById('cash-info');

            function toggleFields() {
                const jenis = jenisSelect.value;

                bankFields.classList.add('hidden');
                eWalletFields.classList.add('hidden');
                cashInfo.classList.add('hidden');

                if (jenis === 'bank_transfer') {
                    bankFields.classList.remove('hidden');
                } else if (jenis === 'e_wallet') {
                    eWalletFields.classList.remove('hidden');
                } else if (jenis === 'cash') {
                    cashInfo.classList.remove('hidden');
                }
            }

            jenisSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endpush
