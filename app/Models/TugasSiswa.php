<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TugasSiswa extends Pivot
{
    protected $table = 'tugas_siswa';
    protected $guarded = [];
}
