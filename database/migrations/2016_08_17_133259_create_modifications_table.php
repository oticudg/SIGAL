<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('movimiento');
            $table->enum('naturaleza', ['entrada', 'salida']);
            $table->integer('original_documento');
            $table->integer('original_tercero');
            $table->integer('updated_documento')->nullable();
            $table->integer('updated_tercero')->nullable();
            $table->integer('deposito');
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
        Schema::drop('modifications');
    }
}
