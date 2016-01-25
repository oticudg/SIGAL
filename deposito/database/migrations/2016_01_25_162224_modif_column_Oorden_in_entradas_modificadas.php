<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifColumnOordenInEntradasModificadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `entradas_modificadas` MODIFY `Oorden` varchar(255) NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `entradas_modificadas` MODIFY `Oorden` varchar(255) NOT NULL;');
    }
}
