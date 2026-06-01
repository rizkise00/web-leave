<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Web Cuti</title>
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

    {{-- Decorative grid overlay --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
                background-size: 36px 36px;"></div>

    {{-- Main container --}}
    <div class="relative z-10 w-full max-w-4xl flex rounded-3xl shadow-2xl overflow-hidden" style="min-height:520px;">

        {{-- Left panel - branding --}}
        <div class="hidden lg:flex flex-col justify-between w-5/12 p-10 relative overflow-hidden"
             style="background: linear-gradient(160deg, rgba(48,49,139,0.85) 0%, rgba(74,26,138,0.75) 100%);
                    backdrop-filter: blur(12px);">

            {{-- Decorative ring --}}
            <div class="absolute -bottom-20 -left-20 w-72 h-72 rounded-full border-2 border-white/10"></div>
            <div class="absolute -bottom-10 -left-10 w-48 h-48 rounded-full border border-white/10"></div>
            <div class="absolute top-20 -right-16 w-56 h-56 rounded-full border border-white/10"></div>

            {{-- Brand --}}
            <div>
                <div class="w-14 h-14 bg-accent rounded-2xl flex items-center justify-center shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-white leading-tight">Web Cuti</h1>
                <p class="text-accent font-semibold mt-1 text-sm">Sistem Manajemen Cuti</p>
            </div>

            {{-- Info list --}}
            <div class="space-y-4">
                @foreach([
                    ['icon'=>'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z', 'text'=>'Daftar akun baru dengan mudah'],
                    ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'text'=>'Akun diverifikasi oleh manajer'],
                    ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'text'=>'Ajukan cuti setelah akun disetujui'],
                ] as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                            </svg>
                        </div>
                        <p class="text-white/80 text-sm">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <p class="text-white/40 text-xs">&copy; {{ date('Y') }} Web Cuti. All rights reserved.</p>
        </div>

        {{-- Right panel - form --}}
        <div class="card-glass flex-1 flex flex-col justify-center px-8 py-10 sm:px-12">

            {{-- Mobile brand --}}
            <div class="lg:hidden text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-accent rounded-2xl shadow-lg mb-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-primary">Web Cuti</h1>
            </div>

            <div class="mb-6">
                <h2 class="text-2xl font-extrabold text-primary">Buat Akun Baru</h2>
                <p class="text-gray-500 text-sm mt-1">Daftarkan diri Anda &mdash; akun akan diaktifkan setelah persetujuan manajer</p>
            </div>

            {{-- Error alert --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 flex items-start gap-3">
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

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4" id="formRegister">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            placeholder="Nama lengkap Anda" required autofocus
                            class="w-full pl-10 pr-4 py-3 border-2 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                focus:ring-0 focus:border-primary transition-colors duration-200
                                {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white' }}">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="nama@perusahaan.com" required
                            class="w-full pl-10 pr-4 py-3 border-2 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                focus:ring-0 focus:border-primary transition-colors duration-200
                                {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white' }}">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password"
                            placeholder="Min. 6 karakter" required
                            class="w-full pl-10 pr-11 py-3 border-2 rounded-xl text-sm text-gray-800 placeholder-gray-400
                                focus:ring-0 focus:border-primary transition-colors duration-200
                                {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white' }}">
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

                {{-- Password confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi kata sandi" required
                            class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 bg-gray-50 hover:border-gray-300 focus:bg-white
                                rounded-xl text-sm text-gray-800 placeholder-gray-400
                                focus:ring-0 focus:border-primary transition-colors duration-200">
                    </div>
                </div>

                {{-- Submit --}}
                <button id="btnRegister" type="submit"
                    class="w-full py-3.5 px-4 rounded-xl font-bold text-sm text-white shadow-lg
                        active:scale-[0.98] transition-all duration-200
                        flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    style="background: linear-gradient(135deg, #30318B 0%, #4a1a8a 100%);
                           box-shadow: 0 4px 20px rgba(48,49,139,0.45);">
                    <svg id="btnRegisterIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span id="btnRegisterText">Daftar Sekarang</span>
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Masuk di sini</a>
            </p>

            <p class="text-center text-xs text-gray-400 mt-2">
                &copy; {{ date('Y') }} Web Cuti &mdash; Sistem Manajemen Cuti Karyawan
            </p>
        </div>
    </div>

    <script>
        document.getElementById('formRegister').addEventListener('submit', function () {
            const btn  = document.getElementById('btnRegister');
            const icon = document.getElementById('btnRegisterIcon');
            const text = document.getElementById('btnRegisterText');

            btn.disabled = true;
            text.textContent = 'Memproses...';
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>`;
            icon.classList.add('animate-spin');
        });

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
