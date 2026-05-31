@extends('layouts.app')
@section('title', 'Ajukan Cuti')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('user.cuti.index') }}" class="text-gray-400 hover:text-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-primary">Ajukan Cuti</h1>
            <p class="text-accent font-medium text-sm mt-0.5">Isi formulir pengajuan cuti di bawah ini.</p>
        </div>
    </div>

    {{-- Kuota info --}}
    <div class="bg-primary/5 border border-primary/20 rounded-xl px-4 py-3 flex items-center gap-3">
        <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-primary">
            Sisa kuota cuti tahunan Anda: <strong>{{ $sisa }} hari</strong>
        </p>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('user.cuti.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Jenis Cuti --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                    Jenis Cuti <span class="text-red-500">*</span>
                </label>
                <select name="jenis_cuti" required
                    class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-800
                        focus:outline-none focus:border-primary focus:bg-white transition-colors
                        {{ $errors->has('jenis_cuti') ? 'border-red-400' : '' }}">
                    <option value="" disabled selected>Pilih jenis cuti...</option>
                    @foreach(\App\Models\Cuti::JENIS_LABEL as $value => $label)
                        <option value="{{ $value }}" {{ old('jenis_cuti') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                        value="{{ old('tanggal_mulai') }}"
                        min="{{ now()->toDateString() }}"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-800
                            focus:outline-none focus:border-primary focus:bg-white transition-colors
                            {{ $errors->has('tanggal_mulai') ? 'border-red-400' : '' }}">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                        value="{{ old('tanggal_selesai') }}"
                        min="{{ now()->toDateString() }}"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-800
                            focus:outline-none focus:border-primary focus:bg-white transition-colors
                            {{ $errors->has('tanggal_selesai') ? 'border-red-400' : '' }}">
                </div>
            </div>

            {{-- Durasi preview --}}
            <div id="durasi_info" class="hidden bg-accent/10 border border-accent/20 rounded-xl px-4 py-2.5 text-sm text-accent font-medium"></div>

            {{-- Alasan --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                    Alasan / Keterangan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan" rows="3" required maxlength="500"
                    placeholder="Tuliskan alasan pengajuan cuti Anda..."
                    class="w-full px-4 py-3 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm text-gray-800
                        placeholder-gray-400 focus:outline-none focus:border-primary focus:bg-white
                        transition-colors resize-none {{ $errors->has('alasan') ? 'border-red-400' : '' }}">{{ old('alasan') }}</textarea>
                <p class="text-xs text-gray-400 mt-1 text-right"><span id="charCount">0</span>/500</p>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3
                        bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl
                        shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kirim Pengajuan
                </button>
                <a href="{{ route('user.cuti.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-200 text-gray-600
                        hover:border-gray-300 text-sm font-semibold rounded-xl transition-all active:scale-95">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const mulai   = document.getElementById('tanggal_mulai');
    const selesai = document.getElementById('tanggal_selesai');
    const info    = document.getElementById('durasi_info');
    const textarea = document.querySelector('textarea[name="alasan"]');
    const charCount = document.getElementById('charCount');

    function updateDurasi() {
        if (!mulai.value || !selesai.value) { info.classList.add('hidden'); return; }
        const a = new Date(mulai.value), b = new Date(selesai.value);
        if (b < a) { info.classList.add('hidden'); return; }
        const days = Math.round((b - a) / 86400000) + 1;
        info.textContent = `Durasi: ${days} hari`;
        info.classList.remove('hidden');
    }

    mulai.addEventListener('change', () => {
        if (selesai.value && selesai.value < mulai.value) selesai.value = mulai.value;
        selesai.min = mulai.value;
        updateDurasi();
    });
    selesai.addEventListener('change', updateDurasi);
    updateDurasi();

    textarea.addEventListener('input', () => {
        charCount.textContent = textarea.value.length;
    });
    charCount.textContent = textarea.value.length;
</script>
@endpush
