<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookkeepingColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->integer('bookkeeping_id')->unsigned()->default(1);
        });
        Schema::table('cards', function (Blueprint $table) {
            $table->integer('bookkeeping_id')->unsigned()->default(1);
        });
        Schema::table('tokens', function (Blueprint $table) {
            $table->integer('bookkeeping_id')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->dropColumn('bookkeeping_id');
        });
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('bookkeeping_id');
        });
        Schema::table('tokens', function (Blueprint $table) {
            $table->dropColumn('bookkeeping_id');
        });
    }
}
