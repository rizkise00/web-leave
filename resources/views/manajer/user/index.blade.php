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

    {{-- Pending approval banner --}}
    @php $pendingCount = $users->filter(fn($u) => $u->account_status === 'pending')->count(); @endphp
    @if($pendingCount > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl px-5 py-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-yellow-800">
                    {{ $pendingCount }} akun menunggu persetujuan
                </p>
                <p class="text-xs text-yellow-600 mt-0.5">Tinjau dan setujui atau tolak pendaftaran di bawah ini.</p>
            </div>
        </div>
    @endif

    {{-- Tabs --}}
    <div>
        <div class="flex gap-1 bg-gray-100 rounded-xl p-1 w-fit">
            <button id="tabBtnAll" onclick="switchTab('all')"
                class="px-4 py-2 rounded-lg text-sm transition-all bg-white text-primary shadow-sm font-semibold">
                Semua User
                <span class="ml-1.5 text-xs bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded-full">{{ $users->total() }}</span>
            </button>
            <button id="tabBtnPending" onclick="switchTab('pending')"
                class="px-4 py-2 rounded-lg text-sm transition-all text-gray-500 hover:text-gray-700">
                Menunggu Persetujuan
                @if($pendingCount > 0)
                    <span class="ml-1.5 text-xs bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded-full font-bold">{{ $pendingCount }}</span>
                @endif
            </button>
        </div>

        {{-- All Users Tab --}}
        <div id="tabAll" class="mt-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800 text-sm">Daftar Semua Pengguna</h2>
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
                                    <th class="text-left px-6 py-3 font-semibold">Status Akun</th>
                                    <th class="text-left px-6 py-3 font-semibold">Sisa Cuti</th>
                                    <th class="text-left px-6 py-3 font-semibold">Total Pengajuan</th>
                                    <th class="text-left px-6 py-3 font-semibold">Bergabung</th>
                                    <th class="px-6 py-3 font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors {{ $user->account_status === 'pending' ? 'bg-yellow-50/40' : '' }}">
                                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                            {{ $user->isManajer() ? 'bg-accent text-white' : 'bg-primary/10 text-primary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->account_status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Disetujui
                                            </span>
                                        @elseif($user->account_status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Menunggu
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Ditolak
                                            </span>
                                        @endif
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
                                        <div class="flex items-center gap-2 justify-center flex-wrap">
                                            @if($user->account_status === 'pending' && $user->id !== Auth::id())
                                                <form action="{{ route('manajer.user.approve', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" onclick="confirmApprove(this.closest('form'), '{{ $user->name }}')"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('manajer.user.reject', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="button" onclick="confirmReject(this.closest('form'), '{{ $user->name }}')"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg transition-all active:scale-95">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif
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

        {{-- Pending Approval Tab --}}
        <div id="tabPending" class="mt-4 hidden">
            @php $pendingUsers = $users->filter(fn($u) => $u->account_status === 'pending'); @endphp
            <div class="bg-white rounded-2xl border border-yellow-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4">
                    <h2 class="font-bold text-primary">Pendaftaran Menunggu Persetujuan</h2>
                    <p class="text-sm text-accent mt-0.5">Tinjau akun yang mendaftar sendiri dan belum disetujui.</p>
                </div>

                @if($pendingUsers->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="font-semibold text-primary">Tidak ada pendaftaran yang menunggu</p>
                        <p class="text-sm text-accent mt-1">Semua akun sudah ditinjau.</p>
                    </div>
                @else
                    <div class="divide-y divide-yellow-50">
                        @foreach($pendingUsers as $user)
                        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Mendaftar {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($user->id !== Auth::id())
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <form action="{{ route('manajer.user.approve', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" onclick="confirmApprove(this.closest('form'), '{{ $user->name }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('manajer.user.reject', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="button" onclick="confirmReject(this.closest('form'), '{{ $user->name }}')"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold rounded-xl shadow-sm transition-all active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
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
    const initialTab = '{{ $pendingCount > 0 ? 'pending' : 'all' }}';

    function switchTab(tab) {
        const tabAll     = document.getElementById('tabAll');
        const tabPending = document.getElementById('tabPending');
        const btnAll     = document.getElementById('tabBtnAll');
        const btnPending = document.getElementById('tabBtnPending');

        if (tab === 'all') {
            tabAll.classList.remove('hidden');
            tabPending.classList.add('hidden');
            btnAll.className     = 'px-4 py-2 rounded-lg text-sm transition-all bg-white text-primary shadow-sm font-semibold';
            btnPending.className = 'px-4 py-2 rounded-lg text-sm transition-all text-gray-500 hover:text-gray-700';
        } else {
            tabAll.classList.add('hidden');
            tabPending.classList.remove('hidden');
            btnAll.className     = 'px-4 py-2 rounded-lg text-sm transition-all text-gray-500 hover:text-gray-700';
            btnPending.className = 'px-4 py-2 rounded-lg text-sm transition-all bg-white text-primary shadow-sm font-semibold';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        switchTab(initialTab);
    });

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

    function confirmApprove(form, name) {
        Swal.fire({
            title: 'Setujui akun?',
            html: `Akun <strong>${name}</strong> akan diaktifkan dan dapat masuk ke sistem.`,
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

    function confirmReject(form, name) {
        Swal.fire({
            title: 'Tolak pendaftaran?',
            html: `Akun <strong>${name}</strong> akan ditolak dan tidak dapat masuk ke sistem.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal',
            customClass: { cancelButton: '!text-gray-700' }
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
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
            openModal();
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
