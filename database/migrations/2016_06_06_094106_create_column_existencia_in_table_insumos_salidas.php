<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnExistenciaInTableInsumosSalidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos_salidas', function (Blueprint $table) {
          $table->double('existencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumos_salidas', function (Blueprint $table) {
          $table->dropColumn('existencia');
        });
    }
}
