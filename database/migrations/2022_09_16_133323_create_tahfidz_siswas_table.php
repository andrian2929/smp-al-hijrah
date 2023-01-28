<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTahfidzSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahfidz_siswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('surah');
            $table->integer('ayat_start')->default(1);
            $table->integer('ayat_end');
            $table->boolean('is_tamat')->default(0);
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
        Schema::dropIfExists('tahfidz_siswas');
    }
}
