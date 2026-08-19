<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                    Kategori</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi
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
                        <p class="font-medium text-gray-800">{{ $item->nama_kategori }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->deskripsi ?? '-' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('master.kategori-pengeluaran.show', $item->id_kategori) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('master.kategori-pengeluaran.edit', $item->id_kategori) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_kategori }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_kategori }}"
                                action="{{ route('master.kategori-pengeluaran.destroy', $item->id_kategori) }}"
                                method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-tags text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data kategori pengeluaran</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Kategori" untuk menambahkan</p>
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
            if (confirm('Yakin ingin menghapus kategori pengeluaran ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endpush
