<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKehadiranSiswasTable extends Migration

{
    /** 

     * Run the migrations. 
     * 
     * @return void 
     */

    public function up()

    {
        Schema::create('kehadiran_siswas', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('kehadiran', ['hadir', 'absen', 'telat', 'izin']);
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

        Schema::dropIfExists('kehadiran_siswas');
    }
}
