@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-primary">Profil Saya</h1>
        <p class="text-accent font-medium text-sm mt-0.5">Kelola informasi akun Anda.</p>
    </div>

    {{-- Info card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center gap-5">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-extrabold text-white flex-shrink-0"
             style="background: linear-gradient(135deg, #30318B, #4a1a8a);">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <p class="text-lg font-extrabold text-gray-800">{{ $user->name }}</p>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                    {{ $user->isManajer() ? 'bg-accent text-white' : 'bg-primary/10 text-primary' }}">
                    {{ ucfirst($user->role) }}
                </span>
                @if($user->isUser())
                    <span class="text-xs text-gray-400">Sisa cuti: <strong class="text-primary">{{ $user->sisa_cuti }} hari</strong></span>
                @endif
                @if($user->meta?->tanggal_bergabung)
                    <span class="text-xs text-gray-400">Bergabung: <strong>{{ $user->meta->tanggal_bergabung->format('d M Y') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Baris 1: Informasi Akun & Ubah Password (tinggi sama) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">

            {{-- Informasi Akun --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Informasi Akun</h2>
                </div>
                <div class="px-6 py-5 space-y-4 flex-1">
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-xs space-y-1">
                            @foreach($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors
                                {{ $errors->has('name') ? 'border-red-400' : '' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors
                                {{ $errors->has('email') ? 'border-red-400' : '' }}">
                    </div>
                </div>
            </div>

            {{-- Ubah Password --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Ubah Password</h2>
                </div>
                <div class="px-6 py-5 space-y-4 flex-1">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Password Saat Ini
                        </label>
                        <input type="password" name="current_password" placeholder="Masukkan password saat ini"
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors
                                {{ $errors->has('current_password') ? 'border-red-400' : '' }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Password Baru
                        </label>
                        <input type="password" name="new_password" placeholder="Min. 6 karakter"
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" name="new_password_confirmation" placeholder="Ulangi password baru"
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    </div>
                    <p class="text-xs text-gray-400">Kosongkan jika tidak ingin mengubah password.</p>
                </div>
            </div>

        </div>

        {{-- Baris 2: Data Pribadi (full width) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-sm">Data Pribadi</h2>
            </div>
            <div class="px-6 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Tanggal Bergabung
                        </label>
                        <input type="date" name="tanggal_bergabung"
                            value="{{ old('tanggal_bergabung', $user->meta?->tanggal_bergabung?->toDateString()) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Tanggal Lahir
                        </label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $user->meta?->tanggal_lahir?->toDateString()) }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Kota Kelahiran
                        </label>
                        <input type="text" name="kota_kelahiran"
                            value="{{ old('kota_kelahiran', $user->meta?->kota_kelahiran) }}"
                            placeholder="cth: Jakarta"
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Alamat
                        </label>
                        <input type="text" name="alamat"
                            value="{{ old('alamat', $user->meta?->alamat) }}"
                            placeholder="Alamat lengkap..."
                            class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm
                                focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3 mt-6">
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-dark text-white
                    text-sm font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-6 py-2.5 border-2 border-gray-200 text-gray-600
                    hover:border-gray-300 text-sm font-semibold rounded-xl transition-all active:scale-95">
                Batal
            </a>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
@if(session('success'))
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2000,
        showConfirmButton: false,
        timerProgressBar: true,
    });
});
@endif
</script>
@endpush
