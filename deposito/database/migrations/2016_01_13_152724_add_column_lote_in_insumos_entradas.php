<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLoteInInsumosEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos_entradas', function (Blueprint $table) {
            $table->string('lote')->nullable();
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
            $table->dropColumn('lote');
        });
    }
}
