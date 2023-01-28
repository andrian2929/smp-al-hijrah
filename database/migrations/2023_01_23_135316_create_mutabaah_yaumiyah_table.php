<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutabaahYaumiyahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutabaah_yaumiyah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('qiyamul_lail')->default(false);
            $table->boolean('dhuha')->default(false);
            $table->boolean('tilawah_quran')->default(false);
            $table->boolean('membaca_buku')->default(false);
            $table->boolean('olahraga')->default(false);
            $table->boolean('al_matsurat')->default(false);
            $table->boolean('shoum_sunnah')->default(false);
            $table->boolean('shalat_subuh')->default(false);
            $table->boolean('shalat_dzuhur')->default(false);
            $table->boolean('shalat_ashar')->default(false);
            $table->boolean('shalat_maghrib')->default(false);
            $table->boolean('shalat_isya')->default(false);
            $table->date('tanggal');
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
        Schema::dropIfExists('mutabaah_yaumiyah');
    }
}
