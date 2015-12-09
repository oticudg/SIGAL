<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnInProvedores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provedores', function (Blueprint $table) {
            $table->dropColumn(['telefono', 'direccion', 'contacto', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provedores', function (Blueprint $table) {
            $table->string('telefono');
            $table->string('direccion');
            $table->string('contacto');
            $table->string('email');
        });
    }
}
