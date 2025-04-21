<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    use HasFactory;

    protected $table = 'revisi';

    protected $fillable = [
        'judul_ta_id',
        'user_id',
        'role_type',
        'catatan',
    ];

    public function judulTA()
    {
        return $this->belongsTo(JudulTA::class, 'judul_ta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}