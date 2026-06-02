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
        .mobile-menu-enter { animation: slideDown 0.2s ease-out; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Navbar --}}
    <nav class="navbar-gradient shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Brand --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 flex-shrink-0">
                    <div class="w-9 h-9 bg-accent rounded-xl flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-white font-extrabold text-lg tracking-wide">Web Cuti</span>
                </a>

                {{-- Desktop nav links (manajer only) --}}
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

                {{-- Right: profile + logout (desktop) + hamburger (mobile) --}}
                <div class="flex items-center gap-2">

                    {{-- Profile link — desktop --}}
                    <a href="{{ route('profile') }}"
                        class="hidden md:flex items-center gap-1.5 px-3 py-2 rounded-lg text-white/80 hover:text-white
                            hover:bg-white/10 text-sm font-semibold transition-all group">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                    </a>

                    {{-- Logout — desktop --}}
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="button" id="btnLogout"
                            class="hidden md:flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium
                                text-white/80 hover:text-white hover:bg-white/10 border border-transparent
                                hover:border-white/20 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>

                    {{-- Hamburger — mobile only --}}
                    <button id="mobileMenuBtn" type="button"
                        class="md:hidden flex items-center justify-center w-10 h-10 rounded-xl
                            text-white/80 hover:text-white hover:bg-white/10 transition-all"
                        aria-label="Menu">
                        <svg id="hamburgerIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu drawer --}}
        <div id="mobileMenu" class="md:hidden hidden border-t border-white/10 mobile-menu-enter">
            <div class="max-w-7xl mx-auto px-4 py-3 space-y-1">

                {{-- Nav links (manajer only) --}}
                @if(Auth::user()->isManajer())
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                            {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('manajer.cuti.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                            {{ request()->routeIs('manajer.cuti.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Kelola Cuti
                    </a>
                    <a href="{{ route('manajer.user.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                            {{ request()->routeIs('manajer.user.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Kelola User
                    </a>
                    {{-- Divider --}}
                    <div class="border-t border-white/10 my-1"></div>
                @endif


                {{-- Profile --}}
                <a href="{{ route('profile') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all
                        {{ request()->routeIs('profile') ? 'bg-white/20 text-white' : 'text-white/70 hover:text-white hover:bg-white/10' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="truncate">{{ Auth::user()->name }}</span>
                </a>

                {{-- Logout --}}
                <button type="button" id="btnLogoutMobile"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold
                        text-white/70 hover:text-white hover:bg-white/10 transition-all">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>

                {{-- Bottom padding --}}
                <div class="pb-1"></div>
            </div>
        </div>
    </nav>

    {{-- Main content --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    @stack('scripts')
    <script>
        // Hamburger toggle
        const mobileMenuBtn  = document.getElementById('mobileMenuBtn');
        const mobileMenu     = document.getElementById('mobileMenu');
        const hamburgerIcon  = document.getElementById('hamburgerIcon');
        const closeIcon      = document.getElementById('closeIcon');

        mobileMenuBtn.addEventListener('click', function () {
            const isOpen = !mobileMenu.classList.contains('hidden');
            if (isOpen) {
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            } else {
                mobileMenu.classList.remove('hidden');
                hamburgerIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });

        // Logout confirmation (desktop)
        function triggerLogout() {
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
        }

        document.getElementById('btnLogout').addEventListener('click', triggerLogout);
        document.getElementById('btnLogoutMobile').addEventListener('click', triggerLogout);
    </script>
</body>
</html>
