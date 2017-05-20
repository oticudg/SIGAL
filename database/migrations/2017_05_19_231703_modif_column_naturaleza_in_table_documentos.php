<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifColumnNaturalezaInTableDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos', function (Blueprint $table) {
            DB::statement('ALTER TABLE `documentos` MODIFY `naturaleza` ENUM("entrada","salida","establecer") NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos', function (Blueprint $table) {
            DB::statement('ALTER TABLE `documentos` MODIFY `naturaleza` ENUM("entrada","salida") NOT NULL');
        });
    }
}
