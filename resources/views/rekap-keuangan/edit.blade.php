@extends('layouts.app')

@section('title', 'Edit Pengeluaran - Arrum Tour')
@section('page-title', 'Edit Pengeluaran Lain-lain')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.rekap-keuangan.index') }}" class="text-gray-500 hover:text-yellow-600">Rekap
            Keuangan</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <a href="{{ route('transaksional.rekap-keuangan.show', $item->id_pengeluaran_lain) }}"
            class="text-gray-500 hover:text-yellow-600">Detail</a>
    </li>
    <li class="inline-flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
        <span class="text-gray-500 font-medium">Edit</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h5 class="text-sm font-semibold text-gray-700">Form Edit Pengeluaran Lain-lain</h5>
                <p class="text-xs text-gray-400 mt-0.5">Perbarui data pengeluaran</p>
            </div>

            <form action="{{ route('transaksional.rekap-keuangan.pengeluaran.update', $item->id_pengeluaran_lain) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal"
                                value="{{ old('tanggal', $item->tanggal->format('Y-m-d')) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                            @error('tanggal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Pengeluaran <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_pengeluaran"
                                value="{{ old('nama_pengeluaran', $item->nama_pengeluaran) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                required>
                            @error('nama_pengeluaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                            <input type="text" name="kategori" value="{{ old('kategori', $item->kategori) }}"
                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                                list="kategoriList">
                            <datalist id="kategoriList">
                                @foreach ($kategoriOptions as $kat)
                                    <option value="{{ $kat }}">
                                @endforeach
                            </datalist>
                            @error('kategori')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nominal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                                <input type="text" name="nominal_display" id="nominal_display"
                                    value="{{ old('nominal') ? number_format((int) old('nominal'), 0, ',', '.') : number_format($item->nominal, 0, ',', '.') }}"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-lg"
                                    placeholder="0" oninput="formatRupiahPl(this)" autocomplete="off" required>
                                <input type="hidden" name="nominal" id="nominal"
                                    value="{{ old('nominal', $item->nominal) }}">
                            </div>
                            @error('nominal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm"
                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan', $item->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('transaksional.rekap-keuangan.show', $item->id_pengeluaran_lain) }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition-colors text-sm font-medium shadow-sm hover:shadow">
                            <i class="fas fa-save mr-2"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function formatRupiahPl(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value === '') {
                input.value = '';
                document.getElementById('nominal').value = 0;
                return;
            }
            let number = parseInt(value);
            if (isNaN(number)) number = 0;
            input.value = number.toLocaleString('id-ID');
            document.getElementById('nominal').value = number;
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const raw = document.getElementById('nominal').value;
            if (!raw || raw == '0') {
                e.preventDefault();
                alert('Nominal wajib diisi!');
            }
        });
    </script>
@endpush
