<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AKHDANI Perdin Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/24/outline/style.css">

    @stack('styles')
</head>
<body class="bg-akhdani-light font-sans antialiased">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" id="sidebar">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 px-6 bg-gradient-to-r from-akhdani-primary to-akhdani-orange">
                <h1 class="text-2xl font-bold text-white">AKHDANI</h1>
            </div>

            <!-- User Info -->
            <div class="p-4">
                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-akhdani-primary rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ Auth::user()->role === 'SDM' ? 'HR Manager' : 'Employee' }}
                        </p>
                        @if(Auth::user()->department)
                            <p class="text-xs text-gray-400">{{ Auth::user()->department }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-2 pb-4 space-y-1">
                @if(Auth::user()->isPegawai())
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('trips.index') }}" class="sidebar-link {{ request()->routeIs('trips.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        PerdinKu
                        @php
                            $myPendingTrips = \App\Models\BusinessTrip::where('user_id', Auth::id())->pending()->count();
                        @endphp
                        @if($myPendingTrips > 0)
                            <span class="ml-auto bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $myPendingTrips }}</span>
                        @endif
                    </a>
                @endif

                @if(Auth::user()->isSDM())
                    <a href="{{ route('hr.dashboard') }}" class="sidebar-link {{ request()->routeIs('hr.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('hr.pending-trips') }}" class="sidebar-link {{ request()->routeIs('hr.pending-trips') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Pengajuan Perdin
                        @php
                            $pendingCount = \App\Models\BusinessTrip::pending()->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="ml-auto bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('cities.index') }}" class="sidebar-link {{ request()->routeIs('cities.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Master Kota
                    </a>

                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        Manajemen User
                    </a>
                @endif

                <!-- Divider -->
                <div class="border-t border-gray-200 my-4"></div>

                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link text-left w-full">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-0">
        <!-- Mobile menu button -->
        <div class="lg:hidden">
            <button type="button" class="fixed top-4 left-4 z-50 bg-akhdani-primary text-white p-2 rounded-md" onclick="toggleSidebar()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Header -->
        <header class="header-bar flex justify-between items-center m-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-gray-600">@yield('page-subtitle', 'Welcome to AKHDANI Perdin Management')</p>
            </div>

            <div class="flex items-center space-x-4">
                <span class="hidden sm:block text-sm text-gray-500">{{ now()->format('l, d F Y') }}</span>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-akhdani-primary">
                        <div class="w-8 h-8 bg-akhdani-primary rounded-full flex items-center justify-center text-white text-sm font-medium">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="hidden sm:block ml-2 text-gray-700">{{ Auth::user()->name }}</span>
                        <svg class="hidden sm:block ml-1 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <div class="px-4 py-2 text-xs text-gray-400 border-b">
                            {{ Auth::user()->email }}
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="content-card m-4 overflow-visible max-h-full">
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-700">{{ session('success') }}</p>
                        <button type="button" class="ml-auto text-green-400 hover:text-green-600" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-red-700">{{ session('error') }}</p>
                        <button type="button" class="ml-auto text-red-400 hover:text-red-600" onclick="this.parentElement.parentElement.remove()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Sidebar overlay for mobile -->
<div class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[onclick*="remove"]');
        alerts.forEach(function(alert) {
            if (alert.parentElement && alert.parentElement.parentElement) {
                alert.click();
            }
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
