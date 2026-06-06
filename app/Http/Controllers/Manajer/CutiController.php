<?php

namespace App\Http\Controllers\Manajer;

use App\Http\Controllers\Controller;
use App\Mail\CutiDiproses;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CutiController extends Controller
{
    public function index()
    {
        $cutis = Cuti::with('user')->latest()->paginate(15);

        $counts = [
            'pending'   => Cuti::where('status', 'pending')->count(),
            'disetujui' => Cuti::where('status', 'disetujui')->count(),
            'ditolak'   => Cuti::where('status', 'ditolak')->count(),
        ];

        return view('manajer.cuti.index', compact('cutis', 'counts'));
    }

    public function approve(Cuti $cuti)
    {
        if ($cuti->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $cuti->update([
            'status'         => 'disetujui',
            'disetujui_oleh' => Auth::id(),
            'disetujui_at'   => now(),
        ]);

        $cuti->user->decrement('sisa_cuti', $cuti->jumlah_hari);

        // Kirim notifikasi email ke user
        Mail::to($cuti->user->email)->send(new CutiDiproses($cuti->load('user')));

        return back()->with('success', "Cuti {$cuti->user->name} berhasil disetujui.");
    }

    public function reject(Request $request, Cuti $cuti)
    {
        if ($cuti->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $request->validate([
            'catatan_manajer' => ['nullable', 'string', 'max:300'],
        ]);

        $cuti->update([
            'status'          => 'ditolak',
            'catatan_manajer' => $request->catatan_manajer,
            'disetujui_oleh'  => Auth::id(),
            'disetujui_at'    => now(),
        ]);

        // Kirim notifikasi email ke user
        Mail::to($cuti->user->email)->send(new CutiDiproses($cuti->load('user')));

        return back()->with('success', "Cuti {$cuti->user->name} berhasil ditolak.");
    }
}
