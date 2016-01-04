<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnTypeInInsumosEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos_entradas', function (Blueprint $table) {
            $table->enum('type', ['orden', 'donacion', 'devolucion']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumos_entradas', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
