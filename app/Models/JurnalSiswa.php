<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalSiswa extends Model
{
    use HasFactory;

    protected $table = 'jurnal_siswa';
    protected $guarded = [];

    public function kehadiranSiswa()
    {
        return $this->belongsTo(KehadiranSiswa::class, 'kehadiran_siswa_id', 'id');
    }
}
