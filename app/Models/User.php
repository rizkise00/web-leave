<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'sisa_cuti',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function meta()
    {
        return $this->hasOne(\App\Models\UserMeta::class);
    }

    public function cutis()
    {
        return $this->hasMany(\App\Models\Cuti::class);
    }

    public function isManajer(): bool
    {
        return $this->role === 'manajer';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
