<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount([
            'cutis as total_cuti',
            'cutis as cuti_pending' => fn ($q) => $q->where('status', 'pending'),
        ])->latest()->paginate(15);

        return view('manajer.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'role'      => ['required', 'in:user,manajer'],
            'sisa_cuti' => ['required_if:role,user', 'integer', 'min:0', 'max:365'],
            'password'  => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'sisa_cuti' => $data['role'] === 'user' ? $data['sisa_cuti'] : 0,
            'password'  => Hash::make($data['password']),
        ]);

        return redirect()->route('manajer.user.index')
            ->with('success', "User {$data['name']} berhasil ditambahkan.");
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'      => ['required', 'in:user,manajer'],
            'sisa_cuti' => ['required_if:role,user', 'integer', 'min:0', 'max:365'],
            'password'  => ['nullable', 'string', 'min:6'],
        ]);

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'sisa_cuti' => $data['role'] === 'user' ? $data['sisa_cuti'] : $user->sisa_cuti,
            ...($data['password'] ? ['password' => Hash::make($data['password'])] : []),
        ]);

        return redirect()->route('manajer.user.index')
            ->with('success', "User {$user->name} berhasil diperbarui.");
    }

    public function approve(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('manajer.user.index')
                ->with('error', 'Tidak dapat menyetujui akun Anda sendiri.');
        }

        $user->update(['account_status' => 'approved']);

        return redirect()->route('manajer.user.index')
            ->with('success', "Akun {$user->name} berhasil disetujui.");
    }

    public function reject(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('manajer.user.index')
                ->with('error', 'Tidak dapat menolak akun Anda sendiri.');
        }

        $user->update(['account_status' => 'rejected']);

        return redirect()->route('manajer.user.index')
            ->with('success', "Akun {$user->name} telah ditolak.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('manajer.user.index')
                ->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('manajer.user.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }
}
