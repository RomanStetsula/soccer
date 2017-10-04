<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirtualPlayer;
use App\Models\RealPlayer;
use App\Models\PlayersRelation;

class PlayerRelationController extends Controller
{
    public function show(){
       $player_relations =  PlayersRelation::all();
        $i = 0;
       foreach($player_relations as $player_relation){

           $records[$i]['real'] = $player_relation->getReal;
           $records[$i]['virtual'] = $player_relation->getVirtual;
            $i++;
       }
        
        return view('perspective', ['records' => $records]);
    }
}
