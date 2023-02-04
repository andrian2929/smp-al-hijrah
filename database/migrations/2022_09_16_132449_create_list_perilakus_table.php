<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListPerilakusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_perilakus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['ibadah', 'akidah', 'akhlak', 'kedisiplinan', 'kebersihandankerapian']);
            $table->boolean('is_terpuji');
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
        Schema::dropIfExists('list_perilakus');
    }
}
