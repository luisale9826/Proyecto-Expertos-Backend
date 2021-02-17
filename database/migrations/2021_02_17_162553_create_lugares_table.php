<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLugaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lugares', function (Blueprint $table) {
            $table->id('id_lugar')->autoIncrement();

            $table->string('nombre');
            $table->string('descripcion', 5000);
            $table->string('categoria');
            $table->decimal('latitud', 17, 15);
            $table->decimal('longitud', 17, 15);
            $table->string('url_foto')->nullable();
            $table->string('url_video')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lugares');
    }
}
