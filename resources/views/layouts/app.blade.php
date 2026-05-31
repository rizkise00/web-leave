<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Web Cuti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .navbar-gradient {
            background: linear-gradient(135deg, #1a1b6b 0%, #30318B 60%, #4a1a8a 100%);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Navbar --}}
    <nav class="navbar-gradient shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Brand --}}
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-accent rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-wide">Web Cuti</span>
                </div>

                {{-- Manajer nav links --}}
                @if(Auth::user()->isManajer())
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                            {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('manajer.cuti.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                            {{ request()->routeIs('manajer.cuti.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        Kelola Cuti
                    </a>
                    <a href="{{ route('manajer.user.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all
                            {{ request()->routeIs('manajer.user.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        Kelola User
                    </a>
                </div>
                @endif

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile') }}"
                        class="hidden sm:flex items-center gap-1.5 text-white text-sm font-semibold
                            hover:text-white/80 transition-colors group">
                        {{ Auth::user()->name }}
                        <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-white/80 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>

                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="button" id="btnLogout"
                            class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium
                                text-white/80 hover:text-white hover:bg-white/10 border border-transparent
                                hover:border-white/20 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        document.getElementById('btnLogout').addEventListener('click', function () {
            Swal.fire({
                title: 'Keluar dari akun?',
                text: 'Anda akan diarahkan ke halaman login.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#30318B',
                cancelButtonColor: '#e5e7eb',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                customClass: { cancelButton: '!text-gray-700' }
            }).then(function (result) {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>
</body>
</html>
