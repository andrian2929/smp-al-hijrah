<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perilaku extends Model
{

    use HasFactory;
    protected $table = 'laporan_perilaku';
    protected $guarded = [];

    public function list_perilaku()
    {
        return $this->belongsTo(ListPerilaku::class, 'perilaku_id');
    }
}
