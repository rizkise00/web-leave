<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Web Cuti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-login {
            background: linear-gradient(135deg, #1a1b6b 0%, #30318B 30%, #4a1a8a 65%, #f18516 100%);
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            pointer-events: none;
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50%       { transform: translateY(-18px) scale(1.04); }
        }
        @keyframes floatAlt {
            0%, 100% { transform: translateY(0px) scale(1); }
            50%       { transform: translateY(14px) scale(0.97); }
        }
        .animate-float     { animation: float 7s ease-in-out infinite; }
        .animate-float-alt { animation: floatAlt 9s ease-in-out infinite; }
        .animate-float-sm  { animation: float 5s ease-in-out infinite 1.5s; }
        input:focus { outline: none; }
    </style>
</head>
<body class="min-h-screen bg-login flex items-center justify-center p-4 overflow-hidden relative">

    {{-- Decorative blobs --}}
    <div class="blob w-96 h-96 bg-accent animate-float"
         style="top:-80px; left:-80px;"></div>
    <div class="blob w-80 h-80 bg-primary-light animate-float-alt"
         style="bottom:-60px; right:-60px;"></div>
    <div class="blob w-64 h-64 bg-white animate-float-sm"
         style="top:40%; left:-120px;"></div>
    <div class="blob w-56 h-56 bg-accent animate-float-alt"
         style="top:10%; right:5%;"></div>
    <div class="blob w-40 h-40 bg-primary-light animate-float"
         style="bottom:15%; left:10%; opacity:0.15;"></div>

    {{-- Decorative grid overlay --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
                background-size: 36px 36px;"></div>

    {{-- Main container --}}
    <div class="relative z-10 w-full max-w-4xl flex rounded-3xl shadow-2xl overflow-hidden">

        {{-- Left panel - branding --}}
        <div class="hidden lg:flex flex-col justify-between w-1/2 p-10 relative"
             style="background-image: url('{{ asset('assets/background.jpeg') }}');
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                    background-color: #1a1b6b;">

            {{-- Overlay --}}
            <div class="absolute inset-0" style="background: rgba(10, 10, 60, 0.55);"></div>

            {{-- Decorative ring --}}
            <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full border-2 border-white/10"></div>
            <div class="absolute -bottom-10 -left-10 w-48 h-48 rounded-full border border-white/10"></div>
            <div class="absolute top-20 -right-16 w-56 h-56 rounded-full border border-white/10"></div>

            {{-- Brand --}}
            <div class="relative z-10">
                <img src="{{ asset('assets/logo.png') }}" alt="Web Cuti" class="h-14 w-auto mb-6">
                <h1 class="text-3xl font-extrabold text-white leading-tight">Web Cuti</h1>
                <p class="text-accent font-bold mt-1 text-sm">Sistem Manajemen Cuti</p>
            </div>

            {{-- Features list --}}
            <div class="relative z-10 space-y-4">
                @foreach([
                    ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'text'=>'Pengajuan cuti mudah & cepat'],
                    ['icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'text'=>'Persetujuan real-time'],
                    ['icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'text'=>'Laporan & statistik lengkap'],
                ] as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="text-white text-sm">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <p class="relative z-10 text-white text-xs">&copy; {{ date('Y') }} Web Cuti. All rights reserved.</p>
        </div>

        {{-- Right panel - form --}}
        <div class="card-glass flex-1 flex flex-col justify-center px-8 py-10 sm:px-12">

            {{-- Mobile brand (only on small screens) --}}
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('assets/logo.png') }}" alt="Web Cuti" class="h-14 w-auto mx-auto mb-3">
                <h1 class="text-2xl font-extrabold text-primary">Web Cuti</h1>
            </div>

            <div class="mb-7">
                <h2 class="text-2xl font-extrabold text-primary">Selamat Datang</h2>
                <p class="text-gray-500 text-sm mt-1">Masuk untuk melanjutkan ke dashboard Anda</p>
            </div>

            {{-- Register success alert --}}
            @if (session('register_success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm">{{ session('register_success') }}</p>
                </div>
            @endif

            {{-- Account pending/rejected alert --}}
            @if (session('account_status_error'))
                <div class="mb-5 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl px-4 py-3 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm">{{ session('account_status_error') }}</p>
                </div>
            @endif

            {{-- Error alert --}}
            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 flex items-start gap-3">
                    <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@perusahaan.com"
                            required
                            autofocus
                            class="w-full pl-10 pr-4 py-3 border-2 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                focus:ring-0 focus:border-primary transition-colors duration-200
                                {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white' }}">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="w-full pl-10 pr-11 py-3 border-2 border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white
                                rounded-xl text-sm text-gray-800 placeholder-gray-400
                                focus:ring-0 focus:border-primary transition-colors duration-200">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-primary transition-colors">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 rounded border-gray-300 accent-primary">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button id="btnLogin" type="submit"
                    class="w-full py-3.5 px-4 rounded-xl font-bold text-sm text-white shadow-lg
                        active:scale-[0.98] transition-all duration-200
                        flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    style="background: linear-gradient(135deg, #30318B 0%, #4a1a8a 100%);
                           box-shadow: 0 4px 20px rgba(48,49,139,0.45);">
                    <svg id="btnLoginIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span id="btnLoginText">Masuk Sekarang</span>
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-primary hover:underline">Daftar di sini</a>
            </p>

            <p class="text-center text-xs text-white mt-2">
                &copy; {{ date('Y') }} Web Cuti &mdash; Sistem Manajemen Cuti Karyawan
            </p>
        </div>
    </div>

    <script>
        // Login button loading state
        document.querySelector('form[action="{{ route('login') }}"]').addEventListener('submit', function () {
            const btn  = document.getElementById('btnLogin');
            const icon = document.getElementById('btnLoginIcon');
            const text = document.getElementById('btnLoginText');

            btn.disabled = true;
            text.textContent = 'Memproses...';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>`;
            icon.classList.add('animate-spin');
        });

        // Toggle password
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                        a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242
                        M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5
                        c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        });
    </script>

</body>
</html>
