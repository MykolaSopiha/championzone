<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('code');
            $table->string('code_hash')->unique();
            $table->string('cw2');
            $table->date('date');
            $table->bigInteger('balance')->unsigned()->default(0);
            $table->string('currency')->default('RUB');
            $table->integer('user_id')->unsigned();
            $table->string('status')->default('active');
            $table->text('info');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cards');
    }
}
