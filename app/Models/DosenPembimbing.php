<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';

    protected $fillable = [
        'judul_ta_id',
        'dosen_id',
    ];

    public function judulTA()
    {
        return $this->belongsTo(JudulTA::class, 'judul_ta_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
