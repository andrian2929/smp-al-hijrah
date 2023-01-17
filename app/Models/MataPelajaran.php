<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;
    public function getGurusUserAttribute() {
        $gurus = json_decode($this->gurus);
        if(!is_array($gurus))
          return collect([]);
        return User::whereIn('id', $gurus)->select('id', 'name')->get();
      }
}
