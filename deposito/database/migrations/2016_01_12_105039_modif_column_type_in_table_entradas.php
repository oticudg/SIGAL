<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifColumnTypeInTableEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        DB::statement("ALTER TABLE entradas MODIFY COLUMN type ENUM('orden', 'donacion', 'devolucion', 'cinventario')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE entradas MODIFY COLUMN type ENUM('orden', 'donacion', 'devolucion')");
    }
}
