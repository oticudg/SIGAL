<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInsumosSalidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos_salidas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salida');
            $table->integer('insumo');
            $table->double('cantidad');
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
        Schema::drop('insumos_salidas');
    }
}
