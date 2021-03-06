<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TransfermarktPlayer;

class CleenTR_PlayersNationalities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:tr_nationalities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        TransfermarktPlayer::chunk(100, function($players) {
            foreach($players as $player){
                preg_match('/^\w*/', $player->nationality, $matches);
                $player->nationality = $matches[0];
                $player->save();
            }
        });
    }
}
