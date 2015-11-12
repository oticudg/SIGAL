<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePrivilegios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilegios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario')->unique();
            $table->boolean('usuarios');
            $table->boolean('usuarioN');
            $table->boolean('usuarioM');
            $table->boolean('usuarioD');
            $table->boolean('provedores');
            $table->boolean('provedoreN');
            $table->boolean('provedoreM');
            $table->boolean('provedoreD');
            $table->boolean('departamentos');
            $table->boolean('departamentoN');
            $table->boolean('departamentoD');
            $table->boolean('insumos');
            $table->boolean('insumoN');
            $table->boolean('insumoM');
            $table->boolean('insumoD');
            $table->boolean('inventarios');
            $table->boolean('inventarioH');
            $table->boolean('entradas');
            $table->boolean('entradaR');
            $table->boolean('salidas');
            $table->boolean('salidaR');
            $table->boolean('modificaciones');
            $table->boolean('estadisticas');
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
        Schema::drop('privilegios');
    }
}
