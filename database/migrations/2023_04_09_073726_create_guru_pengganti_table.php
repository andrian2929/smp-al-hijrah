<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruPenggantiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guru_pengganti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('guru_pengganti_id')->constrained('gurus')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran_haris')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal');
            $table->time('jam');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guru_pengganti');
    }
}
