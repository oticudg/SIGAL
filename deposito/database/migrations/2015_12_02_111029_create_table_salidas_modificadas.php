<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSalidasModificadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salidas_modificadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('salida');
            $table->integer('Odepartamento');
            $table->integer('Mdepartamento')->nullable();
            $table->integer('usuario');
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
        Schema::drop('salidas_modificadas');
    }
}
