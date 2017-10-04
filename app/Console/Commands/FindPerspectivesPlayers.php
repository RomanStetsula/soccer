<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VirtualPlayer;
use App\Models\RealPlayer;
use App\Models\PlayersRelation;

class FindPerspectivesPlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:perspective';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finding perspective players';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $virtual_players;

    public function __construct()
    {
        parent::__construct();

        $this->virtual_players = VirtualPlayer::all();

    }

    public $engToRus = ['q'=>'к', 'w' =>'в', 'e'=>'эе', 'r'=>'р', 't'=>'т', 'y'=>'йи','u'=>'йюу','i'=>'ий','o'=>'о','p'=>'п','a'=>'а','s'=>'сш','d'=>'д','f'=>'ф','g'=>'г','h'=>'гх','j'=>'джй','k'=>'к','l'=>'л','z'=>'з','x'=>'хкс','c'=>'чцк','v'=>'в','b'=>'б','n'=>'н','m'=>'м'];
//    public $engToRus = ["q"=>"к", "w" =>"в"];
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $virtual_players = VirtualPlayer::all();
        $real_players = RealPlayer::all();
        
        foreach($real_players as $real_player){
            foreach($virtual_players as $virtual_player){
                if($real_player->birth_date == $virtual_player->birth_date &&
                    $real_player->leagve_base_talent > $virtual_player->talent &&
                    $this->filterPlayers($real_player, $virtual_player))
                {
                    $player_relation = new PlayersRelation();
                    $player_relation->real_player_id = $real_player->id;
                    $player_relation->virtual_player_id = $virtual_player->id;
                    $player_relation->save();
                }
            }
        }

        dd('finished');
    }

    public function filterPlayers($real_player, $virtual_player){

        $real_first_arr = $this->makeLatterArr($real_player->firstname);
        $real_last_arr = $this->makeLatterArr($real_player->lastname);
        $virtual_first_arr = $this->makeLatterArr($virtual_player->firstname);
        $virtual_last_arr = $this->makeLatterArr($virtual_player->lastname);

        $i = 0;
        if(isset($this->engToRus[$real_first_arr[0]]) && strpos($this->engToRus[$real_first_arr[0]], $virtual_first_arr[0]) !== false ){
            $i++;
        }
        if(isset($this->engToRus[end($real_first_arr)]) && strpos($this->engToRus[end($real_first_arr)], end($virtual_first_arr)) !== false){
            $i++;
        }
        if(isset($this->engToRus[$real_last_arr[0]]) && strpos($this->engToRus[$real_last_arr[0]], $virtual_last_arr[0]) !== false){
            $i++;
        }
        if(isset($this->engToRus[end($real_last_arr)]) && strpos($this->engToRus[end($real_last_arr)], end($virtual_last_arr)) !== false){
            $i++;
        }

        return ($i>1)?true:false;
    }

    /**
     * @param $text
     * @return array
     *
     */
    public function makeLatterArr($text){
        return preg_split('//u', mb_strtolower($text),-1,PREG_SPLIT_NO_EMPTY);
    }


}
