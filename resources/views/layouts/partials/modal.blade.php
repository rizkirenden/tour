<div id="modal-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden" onclick="closeModal()"></div>

<div id="modal-container" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 id="modal-title" class="text-lg font-semibold text-gray-800">Modal Title</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="modal-body" class="px-6 py-4"></div>
        <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 space-x-2">
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Batal</button>
            <button type="button" id="modal-confirm"
                class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium">Simpan</button>
        </div>
    </div>
</div>

<script>
    function openModal(title, bodyHtml, confirmText = 'Simpan', confirmCallback = null) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-body').innerHTML = bodyHtml;
        document.getElementById('modal-confirm').textContent = confirmText;
        if (confirmCallback) {
            document.getElementById('modal-confirm').onclick = function() {
                confirmCallback();
                closeModal();
            };
        }
        openModal();
    }
</script>
