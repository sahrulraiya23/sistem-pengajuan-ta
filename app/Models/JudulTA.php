<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudulTA extends Model
{
    use HasFactory;

    // Definisi status sebagai konstanta
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_APPROVED_FOR_CONSULTATION = 'approved_for_consultation';
    public const STATUS_REVISED = 'revisi';
    public const STATUS_SUBMIT_REVISED = 'submit_revisi';
    public const STATUS_RE_SUBMITTED_AFTER_CONSULTATION = 're_submitted_after_consultation';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_FINALIZED = 'finalized';

    // Daftar semua status untuk validasi atau penggunaan lain
    public const ALL_STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_SUBMITTED,
        self::STATUS_REJECTED,
        self::STATUS_APPROVED_FOR_CONSULTATION,
        self::STATUS_REVISED,
        self::STATUS_RE_SUBMITTED_AFTER_CONSULTATION,
        self::STATUS_APPROVED,
        self::STATUS_FINALIZED,
    ];


    protected $table = 'judul_ta';

    protected $fillable = [
        'user_id',
        'dosen_saran_id',
        'judul1',
        'judul2',
        'judul3',
        'judul_approved',
        'judul_revisi',
        'status',
        'alasan_penolakan',
        'catatan_kajur',
        'catatan_dosen_saran',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembimbing()
    {
        return $this->hasOne(DosenPembimbing::class, 'judul_ta_id');
    }

    public function revisi()
    {
        return $this->hasMany(Revisi::class);
    }

    public function surat()
    {
        return $this->hasOne(SuratTA::class, 'judul_ta_id');
    }

    /**
     * Relasi many-to-many untuk dosen saran.
     */
    public function dosenSarans()
    {
        return $this->belongsToMany(User::class, 'judul_ta_dosen_sarans', 'judul_ta_id', 'user_id');
    }
}
