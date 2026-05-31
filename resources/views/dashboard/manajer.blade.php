@extends('layouts.app')

@section('title', 'Dashboard Manajer')

@php
    $pending     = \App\Models\Cuti::where('status', 'pending')->count();
    $disetujui   = \App\Models\Cuti::where('status', 'disetujui')->whereYear('updated_at', now()->year)->count();
    $ditolak     = \App\Models\Cuti::where('status', 'ditolak')->whereYear('updated_at', now()->year)->count();
    $totalUser   = \App\Models\User::where('role', 'user')->count();
    $pendingCutis = \App\Models\Cuti::with('user')->where('status', 'pending')->latest()->get();
@endphp

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-primary">Dashboard Manajer</h1>
        <p class="text-accent font-medium text-sm mt-0.5">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Pengajuan Baru</p>
                    <p class="text-3xl font-bold text-primary mt-1">{{ $pending }}</p>
                </div>
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Menunggu persetujuan</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Disetujui</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $disetujui }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Tahun ini</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Ditolak</p>
                    <p class="text-3xl font-bold text-red-500 mt-1">{{ $ditolak }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Tahun ini</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Karyawan</p>
                    <p class="text-3xl font-bold text-accent mt-1">{{ $totalUser }}</p>
                </div>
                <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Karyawan aktif</p>
        </div>
    </div>

    {{-- Pengajuan Permohonan Cuti --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h2 class="font-bold text-gray-800 text-sm">Pengajuan Permohonan Cuti</h2>
            </div>
            @if($pending > 0)
                <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">
                    {{ $pending }} menunggu
                </span>
            @endif
        </div>

        @if($pendingCutis->isEmpty())
            <div class="flex flex-col items-center justify-center py-14 text-center px-6">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-400">Belum ada pengajuan cuti</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3 font-semibold">Karyawan</th>
                            <th class="text-left px-6 py-3 font-semibold">Jenis</th>
                            <th class="text-left px-6 py-3 font-semibold">Periode</th>
                            <th class="text-left px-6 py-3 font-semibold">Durasi</th>
                            <th class="text-left px-6 py-3 font-semibold">Alasan</th>
                            <th class="text-left px-6 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pendingCutis as $cuti)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $cuti->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $cuti->user->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-700 whitespace-nowrap">{{ $cuti->jenis_label }}</td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $cuti->tanggal_mulai->format('d M Y') }}
                                @if($cuti->tanggal_mulai != $cuti->tanggal_selesai)
                                    <br><span class="text-gray-400 text-xs">s/d {{ $cuti->tanggal_selesai->format('d M Y') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $cuti->jumlah_hari }} hari</td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $cuti->alasan }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('manajer.cuti.approve', $cuti) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button"
                                            onclick="confirmApprove(this.closest('form'), '{{ $cuti->user->name }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Setujui
                                        </button>
                                    </form>
                                    <button type="button"
                                        onclick="openReject({{ $cuti->id }}, '{{ $cuti->user->name }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

{{-- Hidden reject forms --}}
@foreach($pendingCutis as $cuti)
    <form id="rejectForm{{ $cuti->id }}" action="{{ route('manajer.cuti.reject', $cuti) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="catatan_manajer" id="catatanInput{{ $cuti->id }}">
    </form>
@endforeach

@endsection

@push('scripts')
<script>
    function confirmApprove(form, name) {
        Swal.fire({
            title: 'Setujui pengajuan?',
            html: `Anda akan menyetujui cuti <strong>${name}</strong>.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal',
            customClass: { cancelButton: '!text-gray-700' }
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    }

    function openReject(id, name) {
        Swal.fire({
            title: 'Tolak pengajuan?',
            html: `Pengajuan cuti <strong>${name}</strong> akan ditolak.<br><br>
                   <textarea id="catatanSwal" rows="3" placeholder="Catatan penolakan (opsional)..."
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-xl text-sm focus:border-red-400 focus:outline-none resize-none mt-1"></textarea>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal',
            customClass: { cancelButton: '!text-gray-700' },
            preConfirm: () => document.getElementById('catatanSwal').value,
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById('catatanInput' + id).value = result.value || '';
                document.getElementById('rejectForm' + id).submit();
            }
        });
    }

    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2500,
            showConfirmButton: false,
            timerProgressBar: true,
        });
    });
    @endif

    @if(session('login_success'))
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            text: 'Selamat datang kembali, {{ Auth::user()->name }}',
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true,
        });
    });
    @endif
</script>
@endpush
