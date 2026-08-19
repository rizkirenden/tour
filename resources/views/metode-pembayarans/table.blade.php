<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Bank
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Bank
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor
                    Rekening</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Atas Nama
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status
                </th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $item)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3">
                        <span
                            class="inline-flex px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-mono font-semibold">
                            {{ $item->kode_bank }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $item->nama_bank }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600 font-mono">{{ $item->nomor_rekening }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm text-gray-600">{{ $item->atas_nama }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <button onclick="toggleStatus({{ $item->id_metode }})"
                            class="px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 {{ $item->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </button>
                        <form id="status-form-{{ $item->id_metode }}"
                            action="{{ route('master.metode-pembayaran.toggle-status', $item->id_metode) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('PATCH')
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('master.metode-pembayaran.show', $item->id_metode) }}"
                                class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('master.metode-pembayaran.edit', $item->id_metode) }}"
                                class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $item->id_metode }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $item->id_metode }}"
                                action="{{ route('master.metode-pembayaran.destroy', $item->id_metode) }}"
                                method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <i class="fas fa-credit-card text-gray-300 text-3xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada data metode pembayaran</p>
                            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Metode" untuk menambahkan</p>
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
            if (confirm('Yakin ingin menghapus metode pembayaran ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        function toggleStatus(id) {
            if (confirm('Ubah status metode pembayaran ini?')) {
                document.getElementById('status-form-' + id).submit();
            }
        }
    </script>
@endpush
