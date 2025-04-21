<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTA extends Model
{
    use HasFactory;

    protected $table = 'surat_ta';

    protected $fillable = [
        'judul_ta_id',
        'nomor_surat',
        'status',
    ];

    public function judulTA()
    {
        return $this->belongsTo(JudulTA::class, 'judul_ta_id');
    }
}