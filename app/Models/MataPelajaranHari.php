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
}
