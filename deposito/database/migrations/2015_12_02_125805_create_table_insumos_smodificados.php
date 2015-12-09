<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInsumosSmodificados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_smodificados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salida');
            $table->integer('insumo');
            $table->double('Osolicitado');
            $table->double('Msolicitado')->nullable();
            $table->double('Odespachado');
            $table->double('Mdespachado');
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
        Schema::drop('insumos_smodificados');
    }
}
