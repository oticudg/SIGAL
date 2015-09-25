<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInventario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('insumo');
            $table->double('existencia');
            $table->double('Cmed')->default(0);
            $table->double('Cmin')->default(0);
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
        Schema::drop('inventarios');
    }
}
