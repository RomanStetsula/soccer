<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SoccerlifePlayerOnTR;
use App\Models\TransfermarktPlayer;
use App\Models\PlayersRelation;
use Carbon\Carbon;

class FindPerspectivePlayers extends Command
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
    protected $description = 'Command description';

    /**
     * @var array
     */
    public $engToRus = ['q'=>'к', 'w' =>'в', 'e'=>'эе', 'r'=>'р', 't'=>'т', 'y'=>'йи','u'=>'йюу','i'=>'ий','o'=>'о','p'=>'п','a'=>'а','s'=>'сш','d'=>'д','f'=>'ф','g'=>'г','h'=>'гх','j'=>'джй','k'=>'к','l'=>'л','z'=>'з','x'=>'хкс','c'=>'чцк','v'=>'в','b'=>'б','n'=>'н','m'=>'м'];


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sl_players = SoccerlifePlayerOnTR::where('transfer_date', '>',  Carbon::now()->timezone('Europe/Moscow'))->count();

        $bar = $this->output->createProgressBar($sl_players);

        $tr_players = TransfermarktPlayer::all();

        SoccerlifePlayerOnTR::where('transfer_date', '>',  Carbon::now()->timezone('Europe/Moscow'))->chunk(100, function($soccerlife_players) use ($bar, $tr_players) {
            foreach ($soccerlife_players as $soccerlife_player)
                {
                    $foundPlayers = $tr_players->filter(function ($value, $key) use($soccerlife_player) {
                            return strpos($value['nationality'], $soccerlife_player->nationality) !== false;
                        })
                        ->where('talent', '>', $soccerlife_player->talent)
                        ->where('birth_date', $soccerlife_player->birth_date);

                    foreach ($foundPlayers as $foundPlayer){
                        if($this->filterPlayers($foundPlayer , $soccerlife_player)){
                            PlayersRelation::firstOrCreate(['tr_player_id' => $foundPlayer->id, 'sl_player_id' => $soccerlife_player->id ]);
                        }
                    }

                    $bar->advance();
                }
            });

        $bar->finish();

        dd('finished');
    }

    /**
     * @param $tr_player
     * @param $soccerlife_player
     * @return bool
     */
    public function filterPlayers($tr_player, $soccerlife_player){

        $real_first_arr = $this->makeLatterArr($tr_player->firstname);
        $real_last_arr = $this->makeLatterArr($tr_player->lastname);
        $virtual_first_arr = $this->makeLatterArr($soccerlife_player->firstname);
        $virtual_last_arr = $this->makeLatterArr($soccerlife_player->lastname);

        $i = 0;

        if($tr_player->firstname && $soccerlife_player->firstname){

            if(isset($this->engToRus[$real_first_arr[0]]) && strpos($this->engToRus[$real_first_arr[0]], $virtual_first_arr[0]) !== false ){
                $i++;
            }

            if(isset($this->engToRus[end($real_first_arr)]) && strpos($this->engToRus[end($real_first_arr)], end($virtual_first_arr)) !== false){
                $i++;
            }
        }

        if($tr_player->lastname && $soccerlife_player->lastname){
            if(isset($this->engToRus[$real_last_arr[0]]) && strpos($this->engToRus[$real_last_arr[0]], $virtual_last_arr[0]) !== false){
                $i++;
            }
            if(isset($this->engToRus[end($real_last_arr)]) && strpos($this->engToRus[end($real_last_arr)], end($virtual_last_arr)) !== false){
                $i++;
            }
        }

        return ($i>1)?true:false;
    }

    /**
     * @param $text
     * @return array
     *
     */
    public function makeLatterArr($text){
        preg_match_all('#.{1}#uis', mb_strtolower($text), $out);
        return $out[0];
    }

}
