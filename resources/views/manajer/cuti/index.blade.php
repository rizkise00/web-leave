@extends('layouts.app')
@section('title', 'Kelola Cuti')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-primary">Kelola Cuti</h1>
        <p class="text-accent font-medium text-sm mt-0.5">Semua data pengajuan cuti karyawan.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Menunggu</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $counts['pending'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Disetujui</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $counts['disetujui'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Ditolak</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $counts['ditolak'] }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm">Semua Pengajuan</h2>
        </div>

        @if($cutis->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
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
                            <th class="text-left px-6 py-3 font-semibold">Status</th>
                            <th class="text-left px-6 py-3 font-semibold">Catatan</th>
                            <th class="text-left px-6 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($cutis as $cuti)
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $cuti->status_color }}">
                                    {{ $cuti->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-xs max-w-xs truncate">
                                {{ $cuti->catatan_manajer ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($cuti->status === 'pending')
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
                                @else
                                    <span class="text-xs text-gray-300">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($cutis->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $cutis->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

@foreach($cutis as $cuti)
    @if($cuti->status === 'pending')
        <form id="rejectForm{{ $cuti->id }}" action="{{ route('manajer.cuti.reject', $cuti) }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="catatan_manajer" id="catatanInput{{ $cuti->id }}">
        </form>
    @endif
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
</script>
@endpush
