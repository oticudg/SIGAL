<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInsumosEModificados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_Emodificados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entrada');
            $table->integer('insumo');
            $table->double('Ocantidad');
            $table->double('Mcantidad');
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
        Schema::drop('insumos_modificados');
    }
}
