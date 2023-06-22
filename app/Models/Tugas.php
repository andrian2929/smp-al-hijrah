<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $guarded = [];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaranHari::class, 'mata_pelajaran_id');
    }

    public function nilai()
    {
        return $this->belongsToMany(Siswa::class, 'tugas_siswa', 'tugas_id', 'siswa_id')
            ->withPivot('nilai')->with('user')
            ->withTimestamps();
    }
}
