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
            $table->string('name', 255); //nickname
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->date('birthday');
            $table->boolean('male');
            $table->string('avatar');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('terra_id')->unsigned()->unique()->nullable();
            $table->string('status')->default('mediabuyer');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
