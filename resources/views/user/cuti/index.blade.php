@extends('layouts.app')
@section('title', 'Riwayat Cuti')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-primary">Riwayat Cuti</h1>
            <p class="text-accent font-medium text-sm mt-0.5">Daftar semua pengajuan cuti Anda.</p>
        </div>
        <a href="{{ route('user.cuti.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl shadow-sm transition-all active:scale-95 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Cuti
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Kuota card --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Kuota Tahunan</p>
            <p class="text-3xl font-bold text-primary mt-1">{{ \App\Models\Cuti::KUOTA_TAHUNAN }}</p>
            <p class="text-xs text-gray-400 mt-1">Hari/tahun</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Sisa Kuota</p>
            <p class="text-3xl font-bold mt-1 {{ $sisa > 3 ? 'text-primary' : 'text-red-500' }}">{{ $sisa }}</p>
            <p class="text-xs text-gray-400 mt-1">Hari tersisa</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Terpakai</p>
            <p class="text-3xl font-bold text-accent mt-1">{{ \App\Models\Cuti::KUOTA_TAHUNAN - $sisa }}</p>
            <p class="text-xs text-gray-400 mt-1">Hari digunakan</p>
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
                <p class="text-sm font-semibold text-gray-400">Belum ada pengajuan</p>
                <p class="text-xs text-gray-300 mt-1">Klik "Ajukan Cuti" untuk membuat pengajuan baru</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3 font-semibold">Jenis</th>
                            <th class="text-left px-6 py-3 font-semibold">Periode</th>
                            <th class="text-left px-6 py-3 font-semibold">Durasi</th>
                            <th class="text-left px-6 py-3 font-semibold">Alasan</th>
                            <th class="text-left px-6 py-3 font-semibold">Status</th>
                            <th class="text-left px-6 py-3 font-semibold">Catatan</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($cutis as $cuti)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $cuti->jenis_label }}</td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $cuti->tanggal_mulai->format('d M Y') }}
                                @if($cuti->tanggal_mulai != $cuti->tanggal_selesai)
                                    <span class="text-gray-400">—</span>
                                    {{ $cuti->tanggal_selesai->format('d M Y') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $cuti->jumlah_hari }} hari</td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $cuti->alasan }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $cuti->status_color }}">
                                    {{ $cuti->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate">
                                {{ $cuti->catatan_manajer ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($cuti->status === 'pending')
                                    <form action="{{ route('user.cuti.cancel', $cuti) }}" method="POST" class="cancel-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmCancel(this.closest('form'))"
                                            class="text-xs text-red-500 hover:text-red-700 font-semibold hover:underline">
                                            Batalkan
                                        </button>
                                    </form>
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
@endsection

@push('scripts')
<script>
function confirmCancel(form) {
    Swal.fire({
        title: 'Batalkan pengajuan?',
        text: 'Pengajuan cuti ini akan dihapus secara permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#e5e7eb',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tidak',
        customClass: { cancelButton: '!text-gray-700' }
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });
}
</script>
@endpush
