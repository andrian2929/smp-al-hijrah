<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetertibanGuru extends Model
{
    use HasFactory;

    protected $table = 'ketertiban_guru';
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id')->with('user');
    }
}
