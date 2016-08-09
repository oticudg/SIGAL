<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColummDepartamentoM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privilegios', function (Blueprint $table) {
            $table->boolean('departamentoM')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privilegios', function (Blueprint $table) {
               $table->dropColumn('departamentoM');
        });
    }
}
