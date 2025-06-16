<?php

namespace App\Models;


use Filament\Models\Contracts\FilamentUser; // Impor kelas ini
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\JudulTA;
use App\Models\DosenPembimbing;
use App\Models\Revisi;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nomor_induk',
        'peminatan',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function judulTA()
    {
        return $this->hasMany(JudulTA::class);
    }

    public function bimbingan()
    {
        return $this->hasMany(DosenPembimbing::class, 'dosen_id');
    }

    public function revisi()
    {
        return $this->hasMany(Revisi::class);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
