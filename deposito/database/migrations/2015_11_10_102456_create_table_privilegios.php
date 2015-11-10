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
            $table->integer('usuario');
            $table->boolean('usuarios')->nullable();
            $table->boolean('usuarioN')->nullable();
            $table->boolean('usuarioM')->nullable();
            $table->boolean('usuarioD')->nullable();
            $table->boolean('provedores')->nullable();
            $table->boolean('provedoreN')->nullable();
            $table->boolean('provedoreM')->nullable();
            $table->boolean('provedoreD')->nullable();
            $table->boolean('departamentos')->nullable();
            $table->boolean('departamentoN')->nullable();
            $table->boolean('departamentoD')->nullable();
            $table->boolean('insumos')->nullable();
            $table->boolean('insumoN')->nullable();
            $table->boolean('insumoM')->nullable();
            $table->boolean('insumoD')->nullable();
            $table->boolean('inventarios')->nullable();
            $table->boolean('inventarioH')->nullable();
            $table->boolean('entradas')->nullable();
            $table->boolean('entradaR')->nullable();
            $table->boolean('salidas')->nullable();
            $table->boolean('salidaR')->nullable();
            $table->boolean('modificaciones')->nullable();
            $table->boolean('estadisticas')->nullable();
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
