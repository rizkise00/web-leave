@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-primary">Kelola User</h1>
            <p class="text-accent font-medium text-sm mt-0.5">Manajemen akun pengguna sistem.</p>
        </div>
        <button onclick="openModal()"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl shadow-sm transition-all active:scale-95 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800 text-sm">Daftar Pengguna</h2>
        </div>

        @if($users->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-400">Belum ada user</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3 font-semibold">Nama</th>
                            <th class="text-left px-6 py-3 font-semibold">Email</th>
                            <th class="text-left px-6 py-3 font-semibold">Role</th>
                            <th class="text-left px-6 py-3 font-semibold">Sisa Cuti</th>
                            <th class="text-left px-6 py-3 font-semibold">Total Pengajuan</th>
                            <th class="text-left px-6 py-3 font-semibold">Bergabung</th>
                            <th class="px-6 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->isManajer() ? 'bg-accent text-white' : 'bg-primary/10 text-primary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                @if($user->isUser())
                                    <span class="{{ $user->sisa_cuti <= 3 ? 'text-red-500 font-semibold' : 'text-gray-700' }}">
                                        {{ $user->sisa_cuti }} hari
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->total_cuti ?? 0 }}</td>
                            <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 justify-center">
                                    <button type="button"
                                        onclick="openEditModal({{ $user->id }}, @js($user->name), @js($user->email), '{{ $user->role }}', {{ $user->sisa_cuti }})"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary hover:bg-primary-dark text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('manajer.user.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this.closest('form'), '{{ $user->name }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

{{-- Modal Tambah / Edit User --}}
<div id="modalUser" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 id="modalTitle" class="font-bold text-primary text-base">Tambah User</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="formUser" action="{{ route('manajer.user.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                {{-- Errors --}}
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="inputName" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:border-primary focus:bg-white transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="inputEmail" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:border-primary focus:bg-white transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="inputRole" required onchange="toggleSisaCuti(this.value)"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:border-primary focus:bg-white transition-colors">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="manajer" {{ old('role') === 'manajer' ? 'selected' : '' }}>Manajer</option>
                    </select>
                </div>

                <div id="sisaCutiField">
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Sisa Cuti (hari)</label>
                    <input type="number" name="sisa_cuti" id="inputSisaCuti" value="{{ old('sisa_cuti', 12) }}" min="0" max="365"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:border-primary focus:bg-white transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                        Password <span id="passwordNote" class="text-gray-400 font-normal normal-case">(kosongkan jika tidak diubah)</span>
                        <span id="passwordRequired" class="text-red-500 hidden">*</span>
                    </label>
                    <input type="password" name="password" id="inputPassword"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 bg-gray-50 rounded-xl text-sm focus:outline-none focus:border-primary focus:bg-white transition-colors"
                        placeholder="Min. 6 karakter">
                </div>

                <div class="flex gap-3 pt-1">
                    <button type="submit" id="btnSubmitUser"
                        class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span id="btnSubmitText">Simpan</span>
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 border-2 border-gray-200 text-gray-600 hover:border-gray-300 text-sm font-semibold rounded-xl transition-all active:scale-95">
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
    const storeUrl = '{{ route('manajer.user.store') }}';

    function openModal() {
        document.getElementById('modalTitle').textContent = 'Tambah User';
        document.getElementById('formUser').action = storeUrl;
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('formUser').reset();
        document.getElementById('inputSisaCuti').value = 12;
        document.getElementById('passwordNote').classList.remove('hidden');
        document.getElementById('passwordRequired').classList.add('hidden');
        document.getElementById('inputPassword').required = true;
        document.getElementById('btnSubmitText').textContent = 'Simpan';
        toggleSisaCuti('user');
        document.getElementById('modalUser').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(id, name, email, role, sisaCuti) {
        document.getElementById('modalTitle').textContent = 'Edit User';
        document.getElementById('formUser').action = `/manajer/user/${id}`;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('inputName').value = name;
        document.getElementById('inputEmail').value = email;
        document.getElementById('inputRole').value = role;
        document.getElementById('inputSisaCuti').value = sisaCuti;
        document.getElementById('inputPassword').value = '';
        document.getElementById('inputPassword').required = false;
        document.getElementById('passwordNote').classList.remove('hidden');
        document.getElementById('passwordRequired').classList.add('hidden');
        document.getElementById('btnSubmitText').textContent = 'Simpan Perubahan';
        toggleSisaCuti(role);
        document.getElementById('modalUser').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalUser').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function toggleSisaCuti(role) {
        document.getElementById('sisaCutiField').style.display = role === 'user' ? 'block' : 'none';
    }

    function confirmDelete(form, name) {
        Swal.fire({
            title: 'Hapus user?',
            html: `Akun <strong>${name}</strong> dan semua data cutinya akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            customClass: { cancelButton: '!text-gray-700' }
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    }

    // Auto-open modal on validation error
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            const method = document.getElementById('formMethod').value;
            if (method === 'PUT') {
                // reopen edit — tidak bisa recover id, buka tambah saja
                openModal();
            } else {
                openModal();
            }
        });
    @endif

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

    @if(session('error'))
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#30318B',
        });
    });
    @endif
</script>
@endpush
