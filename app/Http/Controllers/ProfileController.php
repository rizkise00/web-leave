<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('meta');
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'                => ['required', 'string', 'max:100'],
            'email'               => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'current_password'    => ['nullable', 'string'],
            'new_password'        => ['nullable', 'string', 'min:6', 'confirmed'],
            // meta
            'kota_kelahiran'      => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'       => ['nullable', 'date'],
            'alamat'              => ['nullable', 'string', 'max:300'],
            'tanggal_bergabung'   => ['nullable', 'date'],
        ]);

        $newPassword = $data['new_password'] ?? null;

        if ($newPassword) {
            if (!Hash::check($data['current_password'] ?? '', $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }
        }

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
            ...($newPassword ? ['password' => Hash::make($newPassword)] : []),
        ]);

        $user->meta()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'kota_kelahiran'    => $data['kota_kelahiran'] ?? null,
                'tanggal_lahir'     => $data['tanggal_lahir'] ?? null,
                'alamat'            => $data['alamat'] ?? null,
                'tanggal_bergabung' => $data['tanggal_bergabung'] ?? null,
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
