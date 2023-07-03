<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
  use HasFactory;
  public function getGurusUserAttribute()
  {
    $gurus = json_decode($this->gurus);
    if (!is_array($gurus))
      return collect([]);
    return User::whereIn('id', $gurus)->select('id', 'name')->get();
  }

  public function hari()
  {
    return $this->hasMany(MataPelajaranHari::class, 'mapel_id');
  }

  public function tugas()
  {
    return $this->hasMany(Tugas::class, 'mata_pelajaran_id');
  }

  /**
   * Get all classes for this subject.
   * 
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function kelas()
  {
    return $this->belongsToMany(Kelas::class, MataPelajaranHari::class, 'mapel_id', 'kelas_id');
  }
}
