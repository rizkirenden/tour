@if (session('success'))
    <div
        class="alert-dismissible mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-start justify-between">
        <div class="flex items-start">
            <i class="fas fa-check-circle text-yellow-500 mt-0.5 mr-3"></i>
            <p class="text-yellow-800 text-sm">{{ session('success') }}</p>
        </div>
        <button type="button" class="text-yellow-500 hover:text-yellow-700" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert-dismissible mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start justify-between">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
            <p class="text-red-800 text-sm">{{ session('error') }}</p>
        </div>
        <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
            <div>
                <p class="font-medium text-red-800 text-sm">Terjadi kesalahan:</p>
                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
