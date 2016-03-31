<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFechaVInInsumosEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos_entradas', function (Blueprint $table) {
            $table->date('fechaV')->nullable();
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
            $table->dropColumn('fechaV');
        });
    }
}
