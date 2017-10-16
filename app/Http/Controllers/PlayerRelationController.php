<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirtualPlayer;
use App\Models\RealPlayer;
use App\Models\PlayersRelation;
use Carbon\Carbon;

class PlayerRelationController extends Controller
{
    public function show(){
       $player_relations =  PlayersRelation::where('checked', null)->get();
        $i = 0;

        $records=[];

       foreach($player_relations as $player_relation){
           $records[$i]['transfermarket'] = $player_relation->getTransfermarkt;
           $records[$i]['soccerlife'] = $player_relation->getSoccerlife;
           $records[$i]['id'] = $player_relation->id;

           if( Carbon::now()->timezone('Europe/Moscow') < $records[$i]['soccerlife']->transfer_date){
               $i++;
           }

       }

        return view('perspective', ['records' => $records]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checked($id)
    {
        $relation = PlayersRelation::find($id);
        $relation->checked = 1;
        $relation->save();

        return 'ok';
    }
}
