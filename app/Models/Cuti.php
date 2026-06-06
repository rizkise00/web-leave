<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $fillable = [
        'user_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'alasan',
        'status',
        'catatan_manajer',
        'disetujui_oleh',
        'disetujui_at',
    ];

    protected $casts = [
        'tanggal_mulai'  => 'date',
        'tanggal_selesai' => 'date',
        'disetujui_at'   => 'datetime',
    ];

    const KUOTA_TAHUNAN = 12;

    const JENIS_LABEL = [
        'tahunan' => 'Cuti Tahunan',
    ];

    const STATUS_LABEL = [
        'pending'   => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
    ];

    const STATUS_COLOR = [
        'pending'   => 'bg-yellow-100 text-yellow-700',
        'disetujui' => 'bg-green-100 text-green-700',
        'ditolak'   => 'bg-red-100 text-red-700',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS_LABEL[$this->jenis_cuti] ?? $this->jenis_cuti;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABEL[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLOR[$this->status] ?? 'bg-gray-100 text-gray-700';
    }

    public static function sisaKuota(int $userId): int
    {
        return \App\Models\User::find($userId)?->sisa_cuti ?? 0;
    }
}
