<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpinionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opiniones', function (Blueprint $table) {
            $table->id('id_opinion')->autoIncrement();
            $table->integer('mejor_mes');
            $table->boolean('alojamiento');
            $table->integer('accesibilidad');
            $table->integer('precio');
            $table->integer('clima');
            $table->integer('comida');
            $table->integer('conexion_internet');
            $table->foreignId('id_lugar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opiniones');
    }
}
