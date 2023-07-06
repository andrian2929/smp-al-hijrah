<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMateriDanTujuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurnal_kelas', function (Blueprint $table) {
            $table->string('materi')->nullable();
            $table->string('tujuan_pembelajaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurnal_kelas', function (Blueprint $table) {
            $table->dropColumn('materi');
            $table->dropColumn('tujuan_pembelajaran');
        });
    }
}
