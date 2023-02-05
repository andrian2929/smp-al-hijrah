<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class KehadiranSiswa extends Model
{
    use HasFactory;

    protected $table = 'kehadiran_siswas';
    protected $fillable = ['user_id', 'tanggal', 'kehadiran', 'keterangan'];
}
