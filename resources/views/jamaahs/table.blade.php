<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID
                    Keberangkatan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sumber</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk Paket
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">JK</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Passport
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Dokumen
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tagihan
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                @php
                    $sumber = $item->sumber_data;
                    $passport = $item->status_passport;
                    $dokumen = $item->status_dokumen;
                @endphp
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 text-gray-500">{{ $data->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-mono">
                            {{ $item->id_keberangkatan }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-gray-800">{{ $item->nama_lengkap }}</p>
                            @if ($sumber['source'] == 'keluarga')
                                <a href="{{ $sumber['link'] }}" class="text-purple-500 hover:text-purple-700"
                                    title="Lihat Keluarga">
                                    <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400">{{ $item->nomor_paspor ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <div class="relative inline-block group">
                            {!! $sumber['badge'] !!}
                            @if ($sumber['source'] == 'keluarga')
                                <div
                                    class="absolute z-10 hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg p-2 w-48 bottom-full left-1/2 -translate-x-1/2 mb-1">
                                    <p class="font-medium mb-1">Dari Keluarga:</p>
                                    <p class="font-semibold">{{ $sumber['nama_keluarga'] }}</p>
                                    <p class="text-gray-300">{{ $sumber['kode_keluarga'] }}</p>
                                    <div
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->produk_paket }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm text-gray-600">{{ $item->jenis_kelamin_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="relative inline-block group">
                            {!! $passport['badge'] !!}
                            @if ($passport['status'] == 'incomplete')
                                <div
                                    class="absolute z-10 hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg p-2 w-48 bottom-full left-1/2 -translate-x-1/2 mb-1">
                                    <p class="font-medium mb-1">Data Passport belum lengkap:</p>
                                    <ul class="list-disc list-inside">
                                        @foreach ($passport['missing'] as $field)
                                            <li>{{ $field }}</li>
                                        @endforeach
                                    </ul>
                                    <div
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="relative inline-block group">
                            {!! $dokumen['badge'] !!}
                            @if ($dokumen['status'] == 'incomplete')
                                <div
                                    class="absolute z-10 hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg p-2 w-48 bottom-full left-1/2 -translate-x-1/2 mb-1">
                                    <p class="font-medium mb-1">Dokumen belum diupload:</p>
                                    <ul class="list-disc list-inside">
                                        @foreach ($dokumen['missing'] as $field)
                                            <li>{{ $field }}</li>
                                        @endforeach
                                    </ul>
                                    <div
                                        class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        {!! $item->status_pembayaran_badge !!}
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                        {{ $item->total_tagihan_setelah_diskon_formatted }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('transaksional.jamaah.show', $item->id_jamaah) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg" title="Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('transaksional.jamaah.edit', $item->id_jamaah) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <a href="{{ route('transaksional.jamaah.pembayaran', $item->id_jamaah) }}"
                                class="p-2 text-green-500 hover:bg-green-50 rounded-lg" title="Bayar">
                                <i class="fas fa-money-bill-wave text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_jamaah }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_jamaah }}"
                                action="{{ route('transaksional.jamaah.destroy', $item->id_jamaah) }}" method="POST"
                                class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-users text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data jamaah</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Jamaah" untuk menambahkan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 px-2">
    {{ $data->withQueryString()->links() }}
</div>

@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm('Yakin ingin menghapus jamaah ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
