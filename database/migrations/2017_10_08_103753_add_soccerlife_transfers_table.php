<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoccerlifeTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soccerlife_players_on_tr', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('nationality')->default('');
            $table->date('birth_date');
            $table->tinyInteger('age')->nullable();
            $table->string('team')->nullable();
            $table->string('position')->default('');
            $table->string('value')->default('');
            $table->smallInteger('skill')->unsigned()->nullable();
            $table->tinyInteger('talent')->nullable();
            $table->string('transfer_value')->nullable();
            $table->string('transfer_date')->nullable();
            $table->string('offers')->nullable();
            $table->string('url')->nullable();
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
        Schema::drop('soccerlife_players_on_tr');
    }
}
