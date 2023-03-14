<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutabaahYaumiyah extends Model
{
    use HasFactory;

    protected $table = 'mutabaah_yaumiyah';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
