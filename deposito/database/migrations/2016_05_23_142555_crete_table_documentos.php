<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreteTableDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->char('abreviatura',2)->unique();
            $table->string('nombre')->unique();
            $table->enum('tipo', ['provedor', 'deposito', 'servicio','interno']);
            $table->enum('naturaleza',['entrada', 'salida']);
            $table->string('uso');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documentos');
    }
}
