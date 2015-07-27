<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTableInsumos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->text('descripcion');
            $table->integer('id_presentacion');
            $table->integer('id_seccion');
            $table->integer('id_medida');
            $table->integer('cant_min');
            $table->integer('cant_max');
            $table->string('marca');
            $table->string('imagen');
            $table->string('ubicacion');
            $table->string('principio_act');
            $table->string('deposito');
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
        Schema::drop('insumos');
    }
}
