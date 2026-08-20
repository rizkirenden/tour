<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kota Tujuan
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Negara</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Durasi
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah
                    Hotel</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga/Orang
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $index => $item)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $data->firstItem() + $index }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->kota_tujuan ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->negara ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm text-gray-600">{{ $item->durasi_hari ?? '-' }} Hari</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $item->hotels->count() }} Hotel
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                        {{ $item->harga_per_orang_formatted }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('master.paket-tour.show', $item->id_paket_tour) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('master.paket-tour.edit', $item->id_paket_tour) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_paket_tour }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_paket_tour }}"
                                action="{{ route('master.paket-tour.destroy', $item->id_paket_tour) }}" method="POST"
                                class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-route text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data paket tour</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Paket Tour" untuk menambahkan</p>
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
            if (confirm('Yakin ingin menghapus paket tour ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
