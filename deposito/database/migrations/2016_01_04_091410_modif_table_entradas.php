<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifTableEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entradas', function (Blueprint $table){
            $table->string('orden')->nullable()->change();
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
        Schema::table('entradas', function (Blueprint $table) {
            $table->string('orden')->change();
            $table->dropColumn('type');
        });
    }
}
