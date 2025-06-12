<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudulTA extends Model
{
    use HasFactory;

    protected $table = 'judul_ta';

    protected $fillable = [
        'user_id',
        'judul1',
        'judul2',
        'judul3',
        'judul_approved',
        'judul_revisi',
        'status',
        'alasan_penolakan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembimbing()
    {
        return $this->hasOne(DosenPembimbing::class);
    }

    public function revisi()
    {
        return $this->hasMany(Revisi::class);
    }

    public function surat()
    {
        return $this->hasOne(SuratTA::class);
    }
}
