<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahfidzSiswa extends Model
{
    use HasFactory;

    protected $table = 'tahfidz_siswas';

    protected $fillable = [
        'user_id',
        'surah',
        'ayat_start',
        'ayat_end',
        'tanggal'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
