<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaranHari extends Model
{
    use HasFactory;
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'id');
    }
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'mata_pelajaran_id', 'id');
    }

    public function siswa()
    {
        return $this->belongsToMany(User::class, Siswa::class, 'kelas_id', 'user_id', 'kelas_id', 'id');
    }
}
