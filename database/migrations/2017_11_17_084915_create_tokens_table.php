<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('user_id')->unsigned();
            $table->integer('card_id')->unsigned();
            $table->integer('card2_id')->unsigned();
            $table->string('card_code');
            $table->string('card2_code');
            $table->bigInteger('value')->unsigned()->nullable();
            $table->string('currency');
            $table->string('action');
            $table->text('ask');
            $table->text('ans');
            $table->string('status');
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
        Schema::drop('tokens');
    }
}
