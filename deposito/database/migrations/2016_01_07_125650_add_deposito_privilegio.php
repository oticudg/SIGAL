<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class addDepositoPrivilegio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privilegios', function (Blueprint $table) {
            $table->boolean('depositos')->default(false);
            $table->boolean('depositoN')->default(false);
            $table->boolean('depositoM')->default(false);
            $table->boolean('depositoD')->default(false);
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
            $table->dropColumn('depositos');
            $table->dropColumn('depositoN');
            $table->dropColumn('depositoM');
            $table->dropColumn('depositoD');
        });
    }
}
