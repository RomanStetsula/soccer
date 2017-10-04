<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransrfermarktPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfermarkt_players', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('team')->nullable();
            $table->date('birth_date');
            $table->tinyInteger('age')->nullable();
            $table->string('position')->default('');
            $table->string('nationality')->default('');
            $table->string('market_value')->default('');
            $table->tinyInteger('talent')->nullable();
            $table->string('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transfermarkt_players');
    }
}
