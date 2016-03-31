<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDepositoInInsumosEmodificados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insumos_emodificados', function (Blueprint $table) {
            $table->integer('deposito');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('insumos_emodificados', function (Blueprint $table) {
            $table->dropColumn('deposito');
        });
    }
}
