<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\CutiDiajukan;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CutiController extends Controller
{
    public function index()
    {
        $cutis = Cuti::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $sisa = Cuti::sisaKuota(Auth::id());

        return view('user.cuti.index', compact('cutis', 'sisa'));
    }

    public function create()
    {
        $sisa = Cuti::sisaKuota(Auth::id());

        return view('user.cuti.create', compact('sisa'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan'          => ['required', 'string', 'max:500'],
        ]);

        $mulai      = \Carbon\Carbon::parse($data['tanggal_mulai']);
        $selesai    = \Carbon\Carbon::parse($data['tanggal_selesai']);
        $jumlahHari = $mulai->diffInDays($selesai) + 1;

        $sisa = Auth::user()->sisa_cuti;
        if ($jumlahHari > $sisa) {
            return back()->withInput()->withErrors([
                'jumlah_hari' => "Kuota tidak cukup. Sisa kuota Anda: {$sisa} hari.",
            ]);
        }

        if ($this->hasOverlap(Auth::id(), $data['tanggal_mulai'], $data['tanggal_selesai'])) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => 'Tanggal bertabrakan dengan pengajuan cuti yang sudah ada.',
            ]);
        }

        $cuti = Cuti::create([
            'user_id'         => Auth::id(),
            'jenis_cuti'      => 'tahunan',
            'tanggal_mulai'   => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'jumlah_hari'     => $jumlahHari,
            'alasan'          => $data['alasan'],
        ]);

        // Kirim notifikasi email ke semua manajer yang aktif
        $managers = User::where('role', 'manajer')
            ->where('account_status', 'approved')
            ->get();

        foreach ($managers as $manajer) {
            Mail::to($manajer->email)->send(new CutiDiajukan($cuti->load('user')));
        }

        return redirect()->route('dashboard')
            ->with('success', 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan.');
    }

    public function update(Request $request, Cuti $cuti)
    {
        if ($cuti->user_id !== Auth::id() || $cuti->status !== 'pending') {
            abort(403);
        }

        $data = $request->validate([
            'tanggal_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan'          => ['required', 'string', 'max:500'],
        ]);

        $mulai      = \Carbon\Carbon::parse($data['tanggal_mulai']);
        $selesai    = \Carbon\Carbon::parse($data['tanggal_selesai']);
        $jumlahHari = $mulai->diffInDays($selesai) + 1;

        $sisa = Auth::user()->sisa_cuti;
        if ($jumlahHari > $sisa) {
            return back()->withInput()->withErrors([
                'jumlah_hari' => "Kuota tidak cukup. Sisa kuota Anda: {$sisa} hari.",
            ]);
        }

        if ($this->hasOverlap(Auth::id(), $data['tanggal_mulai'], $data['tanggal_selesai'], $cuti->id)) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => 'Tanggal bertabrakan dengan pengajuan cuti yang sudah ada.',
            ]);
        }

        $cuti->update([
            'tanggal_mulai'   => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'jumlah_hari'     => $jumlahHari,
            'alasan'          => $data['alasan'],
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    public function cancel(Cuti $cuti)
    {
        if ($cuti->user_id !== Auth::id() || $cuti->status !== 'pending') {
            abort(403);
        }

        $cuti->delete();

        return redirect()->route('dashboard')->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    private function hasOverlap(int $userId, string $mulai, string $selesai, ?int $excludeId = null): bool
    {
        return Cuti::where('user_id', $userId)
            ->whereIn('status', ['pending', 'disetujui'])
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($mulai, $selesai) {
                // Existing cuti starts before or on new end AND ends on or after new start
                $q->whereDate('tanggal_mulai', '<=', $selesai)
                  ->whereDate('tanggal_selesai', '>=', $mulai);
            })
            ->exists();
    }
}
