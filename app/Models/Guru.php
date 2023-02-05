<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'nip', 'gelar', 'tanggal_bergabung'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->with('roles');
    }

    public function kehadiran()
    {
        return $this->hasMany(KehadiranSiswa::class, 'user_id', 'user_id');
    }
}
