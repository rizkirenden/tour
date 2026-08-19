@hasSection('breadcrumb')
    <nav class="flex mb-4 text-sm">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-yellow-600">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            @yield('breadcrumb')
        </ol>
    </nav>
@endif
