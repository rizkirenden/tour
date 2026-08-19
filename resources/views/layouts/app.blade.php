<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Arrum Tour - Umroh & Travel')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
    <div x-data="{ sidebarOpen: true }" class="h-screen overflow-hidden bg-gray-50">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="h-screen flex flex-col transition-all duration-300 overflow-hidden"
            :class="sidebarOpen ? 'ml-64' : 'ml-20'">

            <!-- Header - Sticky di atas -->
            <div class="flex-shrink-0">
                @include('layouts.partials.header')
            </div>

            <!-- Page Content - Scrollable -->
            <main class="flex-1 overflow-y-auto p-2">
                <div class="max-w- mx-auto">
                    @include('layouts.partials.breadcrumb')
                    @include('layouts.partials.alert')

                    @hasSection('page-title')
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-800">@yield('page-title')</h1>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Footer - Sticky di bawah -->
            <div class="flex-shrink-0">
                @include('layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('layouts.partials.modal')

    @vite(['resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        function confirmDelete(id, name = 'data') {
            if (confirm(`Yakin ingin menghapus ${name} ini?`)) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

        function closeModal() {
            document.getElementById('modal-backdrop').classList.add('hidden');
            document.getElementById('modal-container').classList.add('hidden');
        }

        function openModal() {
            document.getElementById('modal-backdrop').classList.remove('hidden');
            document.getElementById('modal-container').classList.remove('hidden');
        }

        setTimeout(function() {
            document.querySelectorAll('.alert-dismissible').forEach(function(el) {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(function() {
                    el.remove();
                }, 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
