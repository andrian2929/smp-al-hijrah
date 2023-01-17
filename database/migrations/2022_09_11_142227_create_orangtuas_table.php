<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrangtuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orangtuas', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['ayah', 'ibu', 'wali']);
            $table->enum('status', ['kandung', 'angkat']);
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->date('tanggal_lahir');
            $table->string('pekerjaan');
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
        Schema::dropIfExists('orangtuas');
    }
}
