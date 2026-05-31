@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $sisa      = Auth::user()->sisa_cuti;
    $terpakai  = \App\Models\Cuti::KUOTA_TAHUNAN - $sisa;
    $pending   = \App\Models\Cuti::where('user_id', Auth::id())->where('status', 'pending')->count();
    $cutis     = \App\Models\Cuti::where('user_id', Auth::id())->latest()->paginate(8);
    $hasErrors = $errors->any();
@endphp

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-primary">Dashboard Saya</h1>
            <p class="text-accent font-medium text-sm mt-0.5">Selamat datang, {{ Auth::user()->name }}!</p>
        </div>
        <button onclick="openModalCuti()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent hover:bg-accent-dark text-white text-sm font-semibold rounded-xl shadow-sm transition-all active:scale-95 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Cuti
        </button>
    </div>


    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Sisa Cuti</p>
                    <p class="text-3xl font-bold text-primary mt-1">{{ $sisa }}</p>
                </div>
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Hari tersisa tahun ini</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Cuti Digunakan</p>
                    <p class="text-3xl font-bold text-accent mt-1">{{ $terpakai }}</p>
                </div>
                <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Hari terpakai</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Menunggu</p>
                    <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $pending }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">Pengajuan pending</p>
        </div>
    </div>

    {{-- Riwayat Cuti --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm">Riwayat Pengajuan Cuti</h2>
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
                            <td class="px-6 py-4 font-medium text-gray-800 whitespace-nowrap">{{ $cuti->jenis_label }}</td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">
                                {{ $cuti->tanggal_mulai->format('d M Y') }}
                                @if($cuti->tanggal_mulai != $cuti->tanggal_selesai)
                                    <span class="text-gray-400">–</span>
                                    {{ $cuti->tanggal_selesai->format('d M Y') }}
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
                                        <button type="button"
                                            onclick="openEditCuti({{ $cuti->id }}, '{{ $cuti->jenis_cuti }}', '{{ $cuti->tanggal_mulai->toDateString() }}', '{{ $cuti->tanggal_selesai->toDateString() }}', @js($cuti->alasan))"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary hover:bg-primary-dark text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('user.cuti.cancel', $cuti) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmCancel(this.closest('form'))"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Batalkan
                                            </button>
                                        </form>
                                    </div>
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

{{-- Modal Ajukan Cuti --}}
<div id="modalCuti" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModalCuti()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative">

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 id="modalTitle" class="font-bold text-primary text-base">Ajukan Cuti</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Sisa kuota tahunan: <strong class="text-primary">{{ $sisa }} hari</strong></p>
                </div>
                <button onclick="closeModalCuti()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal body --}}
            <form id="formCuti" action="{{ route('user.cuti.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                {{-- Error --}}
                @if($hasErrors)
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Jenis --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Jenis Cuti <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_cuti" required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-800
                            focus:outline-none focus:border-primary focus:bg-white transition-colors
                            {{ $errors->has('jenis_cuti') ? 'border-red-400' : '' }}">
                        <option value="" disabled {{ !old('jenis_cuti') ? 'selected' : '' }}>Pilih jenis cuti...</option>
                        @foreach(\App\Models\Cuti::JENIS_LABEL as $value => $label)
                            <option value="{{ $value }}" {{ old('jenis_cuti') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="modalTanggalMulai"
                            value="{{ old('tanggal_mulai') }}"
                            min="{{ now()->toDateString() }}" required
                            class="w-full px-3 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-800
                                focus:outline-none focus:border-primary focus:bg-white transition-colors
                                {{ $errors->has('tanggal_mulai') ? 'border-red-400' : '' }}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" id="modalTanggalSelesai"
                            value="{{ old('tanggal_selesai') }}"
                            min="{{ now()->toDateString() }}" required
                            {{ !old('tanggal_mulai') ? 'disabled' : '' }}
                            class="w-full px-3 py-2.5 border-2 rounded-xl text-sm transition-colors
                                focus:outline-none focus:border-primary focus:bg-white
                                disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed disabled:border-gray-200
                                {{ $errors->has('tanggal_selesai') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 text-gray-800' }}">
                    </div>
                </div>

                {{-- Durasi preview --}}
                <div id="modalDurasi" class="hidden bg-accent/10 border border-accent/20 rounded-xl px-4 py-2 text-sm text-accent font-medium"></div>

                {{-- Alasan --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Alasan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alasan" rows="3" required maxlength="500"
                        placeholder="Tuliskan alasan pengajuan cuti..."
                        class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-800
                            placeholder-gray-400 focus:outline-none focus:border-primary focus:bg-white
                            transition-colors resize-none {{ $errors->has('alasan') ? 'border-red-400' : '' }}">{{ old('alasan') }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-1">
                    <button id="btnKirimCuti" type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 py-2.5
                            bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl
                            shadow-sm transition-all active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed">
                        <svg id="btnKirimIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="btnKirimText">Kirim Pengajuan</span>
                    </button>
                    <button type="button" onclick="closeModalCuti()"
                        class="px-5 py-2.5 border-2 border-gray-200 text-gray-600 hover:border-gray-300
                            text-sm font-semibold rounded-xl transition-all active:scale-95">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const storeUrl = '{{ route('user.cuti.store') }}';

    function openModalCuti() {
        // Reset ke mode create
        document.getElementById('formCuti').action = storeUrl;
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Ajukan Cuti';
        document.getElementById('btnKirimText').textContent = 'Kirim Pengajuan';
        document.getElementById('formCuti').reset();
        document.getElementById('modalTanggalSelesai').disabled = true;
        document.getElementById('modalDurasi').classList.add('hidden');
        document.getElementById('modalCuti').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openEditCuti(id, jenis, mulai, selesai, alasan) {
        const form = document.getElementById('formCuti');
        form.action = `/cuti/${id}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('modalTitle').textContent = 'Edit Pengajuan Cuti';
        document.getElementById('btnKirimText').textContent = 'Simpan Perubahan';

        // Isi field
        form.querySelector('[name="jenis_cuti"]').value = jenis;
        document.getElementById('modalTanggalMulai').value = mulai;
        // Enable selesai dulu sebelum set value
        document.getElementById('modalTanggalSelesai').disabled = false;
        document.getElementById('modalTanggalSelesai').min = mulai;
        document.getElementById('modalTanggalSelesai').value = selesai;
        form.querySelector('[name="alasan"]').value = alasan;

        updateDurasi();
        document.getElementById('modalCuti').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModalCuti() {
        document.getElementById('modalCuti').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Auto-open modal if there are validation errors
    @if($hasErrors)
        document.addEventListener('DOMContentLoaded', openModalCuti);
    @endif

    // Loading button saat kirim pengajuan
    document.getElementById('formCuti').addEventListener('submit', function () {
        const btn  = document.getElementById('btnKirimCuti');
        const icon = document.getElementById('btnKirimIcon');
        const text = document.getElementById('btnKirimText');
        btn.disabled = true;
        text.textContent = 'Memproses...';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>`;
        icon.classList.add('animate-spin');
    });

    // Durasi preview & tanggal selesai enable/disable
    const mulai   = document.getElementById('modalTanggalMulai');
    const selesai = document.getElementById('modalTanggalSelesai');
    const durasi  = document.getElementById('modalDurasi');

    function updateDurasi() {
        if (!mulai.value || !selesai.value) { durasi.classList.add('hidden'); return; }
        const a = new Date(mulai.value), b = new Date(selesai.value);
        if (b < a) { durasi.classList.add('hidden'); return; }
        const days = Math.round((b - a) / 86400000) + 1;
        durasi.textContent = `Durasi: ${days} hari`;
        durasi.classList.remove('hidden');
    }

    mulai.addEventListener('change', () => {
        if (mulai.value) {
            selesai.disabled = false;
            selesai.min = mulai.value;
            // Reset tanggal selesai jika lebih kecil dari mulai
            if (selesai.value && selesai.value < mulai.value) {
                selesai.value = mulai.value;
            }
        } else {
            selesai.disabled = true;
            selesai.value = '';
        }
        updateDurasi();
    });

    selesai.addEventListener('change', updateDurasi);
    updateDurasi();

    // Konfirmasi batalkan
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
        }).then(result => { if (result.isConfirmed) form.submit(); });
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
            text: 'Selamat datang, {{ Auth::user()->name }}',
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true,
        });
    });
    @endif
</script>
@endpush
