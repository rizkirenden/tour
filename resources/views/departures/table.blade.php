<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Keberangkatan</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kuota</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                </th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $index => $item)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3">
                        <span
                            class="inline-flex px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-mono font-semibold">
                            {{ $item->kode_keberangkatan }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $item->nama_keberangkatan }}</p>
                        <p class="text-xs text-gray-400">{{ $item->jamaah_terdaftar }} Jamaah</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->produk_paket }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <p class="text-sm text-gray-600">{{ $item->tanggal_keberangkatan->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-400">Kembali {{ $item->tanggal_kepulangan->format('d/m/Y') }}</p>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center gap-1 text-sm">
                            <span class="font-medium text-gray-800">{{ $item->jamaah_terdaftar }}</span>
                            <span class="text-gray-400">/</span>
                            <span class="text-gray-500">{{ $item->kuota }}</span>
                        </span>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-yellow-500 h-1.5 rounded-full"
                                style="width: {{ $item->kuota > 0 ? ($item->jamaah_terdaftar / $item->kuota) * 100 : 0 }}%">
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        {!! $item->status_badge !!}
                    </td>
                    <td class="px-4 py-3 text-right font-bold text-yellow-600">
                        {{ $item->total_pendapatan_bersih_formatted }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('transaksional.departure.show', $item->id_departure) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('transaksional.departure.edit', $item->id_departure) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_departure }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_departure }}"
                                action="{{ route('transaksional.departure.destroy', $item->id_departure) }}"
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
                                <i class="fas fa-calendar-times text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data keberangkatan</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Keberangkatan" untuk menambahkan
                            </p>
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
            if (confirm('Yakin ingin menghapus keberangkatan ini? Semua data terkait akan dihapus.')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
