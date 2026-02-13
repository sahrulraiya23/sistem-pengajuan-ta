<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    use HasFactory;

    protected $table = 'thesis';

    protected $fillable = [
        'title',
        'abstract',
        'type',
        'author',
        'program_study',
        'year',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public static function getTypes()
    {
        return [
            'skripsi' => 'Skripsi',
            'tesis' => 'Tesis',
            'disertasi' => 'Disertasi'
        ];
    }
}
