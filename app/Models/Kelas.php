<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    public function wali_kelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id', 'id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, Siswa::class, 'kelas_id', 'user_id');
    }
}
