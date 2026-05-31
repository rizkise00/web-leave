<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'user_meta';

    protected $fillable = [
        'user_id',
        'kota_kelahiran',
        'tanggal_lahir',
        'alamat',
        'tanggal_bergabung',
    ];

    protected $casts = [
        'tanggal_lahir'     => 'date',
        'tanggal_bergabung' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
