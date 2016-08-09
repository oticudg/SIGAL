<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifColumnTypeInTableInsumosEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE insumos_entradas MODIFY COLUMN type ENUM('orden', 'donacion', 'devolucion', 'cinventario')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE insumos_entradas MODIFY COLUMN type ENUM('orden', 'donacion', 'devolucion')");
    }
}
