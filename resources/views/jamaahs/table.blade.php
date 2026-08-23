<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID
                    Keberangkatan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Lengkap
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk Paket
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">JK</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Diskon
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
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 text-gray-500">{{ $data->firstItem() + $index }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-mono">
                            {{ $item->id_keberangkatan }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $item->nama_lengkap }}</p>
                        <p class="text-xs text-gray-400">{{ $item->nomor_paspor ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->produk_paket }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm text-gray-600">{{ $item->jenis_kelamin_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if ($item->diskon)
                            <span class="text-xs font-medium text-green-600">
                                {{ $item->diskon->nama_diskon }}<br>
                                <span
                                    class="text-yellow-600 text-xs">-{{ $item->diskon->nilai_diskon_formatted }}</span>
                            </span>
                        @elseif ($item->nilai_diskon > 0)
                            <span class="text-xs font-medium text-yellow-600">
                                -{{ $item->nilai_diskon_formatted }}
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
                    <td colspan="9" class="px-4 py-12 text-center">
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
