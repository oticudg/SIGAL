<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 15);
            $table->string('apellido', 20);
            $table->string('cedula');
            $table->enum('rol',['farmacia','alimentacion']);
            $table->enum('rango',['director','jefe','empleado']);
            $table->string('email',50)->unique();
            $table->string('password', 60);
            $table->string('remember_token');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
