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
            $table->integer('tr_player_id')->unsigned();
            $table->integer('sl_player_id')->unsigned();
            $table->tinyInteger('checked')->unsigned()->nullable();
            $table->foreign('tr_player_id')->references('id')->on('transfermarkt_players');
            $table->foreign('sl_player_id')->references('id')->on('soccerlife_players_on_tr');
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
        Schema::drop('players_relations');
    }
}
