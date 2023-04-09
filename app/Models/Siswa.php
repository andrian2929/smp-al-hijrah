<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{

    use HasFactory;

    protected $table = 'siswas';
    protected $guarded = [];
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kehadiran()
    {
        return $this->hasMany(KehadiranSiswa::class, 'user_id', 'user_id');
    }

    public function nilai()
    {
        return $this->hasMany(NilaiSiswa::class, 'siswa_id', 'id');
    }

    public function nilai_mapel()
    {
        return $this->hasOne(NilaiSiswa::class, 'siswa_id', 'id');
    }

    public function laporan_tahfidz()
    {
        return $this->hasMany(TahfidzSiswa::class, 'user_id', 'user_id');
    }
}
