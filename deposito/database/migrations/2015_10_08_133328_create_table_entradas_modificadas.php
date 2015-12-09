<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEntradasModificadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas_modificadas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entrada');
            $table->integer('Oprovedor');
            $table->integer('Mprovedor')->nullable();
            $table->string('Oorden');
            $table->string('Morden')->nullable();
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
        Schema::drop('entradas_modificadas');
    }
}
