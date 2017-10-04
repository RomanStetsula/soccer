<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablePlayersRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('real_player_id')->unsigned();
            $table->integer('virtual_player_id')->unsigned();
            $table->foreign('real_player_id')->references('id')->on('real_players');
            $table->foreign('virtual_player_id')->references('id')->on('virtual_players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('players_relations');
    }
}
