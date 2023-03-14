<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurnalKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal_kelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_guru');
            $table->string('catatan');
            $table->timestamps();

            $table->foreign('id_kelas')->references('id')->on('kelas');
            $table->foreign('id_guru')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurnal_kelas');
    }
}
