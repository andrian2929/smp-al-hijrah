<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class KehadiranSiswa extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_siswas';
    protected $fillable = ['user_id', 'tanggal', 'kehadiran', 'keterangan'];

    public function jurnalSiswa()
    {
        return $this->hasOne(JurnalSiswa::class, 'kehadir
        an_siswa_id', 'id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id', 'user_id');
    }
}
