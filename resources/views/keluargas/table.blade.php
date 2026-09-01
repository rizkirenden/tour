<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode
                    Keluarga / Kelompok</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                    Keluarga / Kelompok</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jamaah
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Diskon
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tagihan
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $index => $item)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 text-gray-500">{{ $data->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-mono">
                            {{ $item->kode_keluarga }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $item->nama_keluarga }}</p>
                        <p class="text-xs text-gray-400">Agent: {{ $item->agent ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $item->jamaahs->count() }} Jamaah
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($item->diskon)
                            <span class="text-xs font-medium text-green-600">
                                {{ $item->diskon->nama_diskon }}
                                <br>
                                <span class="text-yellow-600">-{{ $item->diskon->nilai_diskon_formatted }}</span>
                            </span>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        {!! $item->status_pembayaran_badge !!}
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                        {{ $item->total_tagihan_setelah_diskon_formatted }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('transaksional.keluarga.show', $item->id_keluarga) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                title="Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('transaksional.keluarga.edit', $item->id_keluarga) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200"
                                title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <a href="{{ route('transaksional.keluarga.pembayaran', $item->id_keluarga) }}"
                                class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-all duration-200"
                                title="Bayar">
                                <i class="fas fa-money-bill-wave text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_keluarga }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                                title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_keluarga }}"
                                action="{{ route('transaksional.keluarga.destroy', $item->id_keluarga) }}"
                                method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-users text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data keluarga / kelompok</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Keluarga / Kelompok" untuk
                                menambahkan</p>
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
            if (confirm('Yakin ingin menghapus keluarga / kelompok ini?\nSemua jamaah juga akan dihapus!')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
