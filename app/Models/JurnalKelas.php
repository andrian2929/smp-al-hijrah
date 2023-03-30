<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalKelas extends Model
{
    use HasFactory;

    protected $table = 'jurnal_kelas';

    protected $fillable = [
        'id_kelas',
        'id_guru',
        'id_mapel',
        'catatan',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'user_id');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaranHari::class, 'id_mapel', 'id')->with('mapel');
    }
}
