<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tleads_id');
            $table->string('offer_id');
            $table->string('stream_id');
            $table->string('tuser_id');
            $table->string('name');
            $table->string('phone');
            $table->string('tz');
            $table->string('address');
            $table->string('country');
            $table->string('check_sum');
            $table->string('status');
            $table->float('cost');
            $table->string('comment');
            $table->string('action');
            $table->string('fields');
            $table->string('date_create');
            $table->string('utm_source');
            $table->string('utm_medium');
            $table->string('utm_campaign');
            $table->string('utm_term');
            $table->string('utm_content');
            $table->string('sub_id');
            $table->string('sub_id_1');
            $table->string('sub_id_2');
            $table->string('sub_id_3');
            $table->string('sub_id_4');
            $table->string('ip');
            $table->string('user_agent');
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
        Schema::drop('leads');
    }
}
