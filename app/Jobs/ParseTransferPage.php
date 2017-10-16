<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;
use App\Jobs\ParseCreateUpdatePlayer;

class ParseTransferPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $players_on_transfer_tag = '.taddy tr';

    /**
     * @var
     */
    public $i;

    public $client;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client, $i)
    {
        $this->i = $i;
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parsed_page = $this->client->request('GET', 'http://www.soccerlife.ru/transfers.php?page='.$this->i);

        $players_on_transfer = $parsed_page->filter($this->players_on_transfer_tag)->reduce(function (Crawler $node, $i) {
            return strpos($node->attr('bgcolor'), '#ffffff');
        });

        $players_on_tr = $players_on_transfer->each(function (Crawler $player, $i) {

            $player_on_tr['position'] = $player->children()->eq(1)->text();
            $player_on_tr['fullname'] = $player->children()->eq(2)->filter('.original')->text();
            $player_on_tr['team'] = $player->children()->eq(3)->filter('.original')->text();
            $player_on_tr['age'] = $player->children()->eq(4)->text();
            $player_on_tr['url'] = 'http://www.soccerlife.ru/'.$player->filter('a.original')->attr('href');
            $player_on_tr['transfer_value'] = trim($player->children()->eq(7)->text());
            $player_on_tr['transfer_date'] = trim($player->children()->eq(8)->text());
            $player_on_tr['offers'] = 'http://www.soccerlife.ru/'.$player->children()->eq(10)->children()->eq(2)->attr('href');
            $player_on_tr['talent'] = trim($player->children()->eq(5)->text());
            $player_on_tr['skill'] = trim($player->children()->eq(6)->text());

            if($player_on_tr['talent']<5){
                return $player_on_tr;
            }

        });

        $players_on_tr = array_filter($players_on_tr, function($player){
            return $player != null;
        });

        foreach($players_on_tr as $player){
//                if(SoccerlifePlayerOnTR::where('url', $player['url'])->get()){
//                    //update player
//                } else {
            ParseCreateUpdatePlayer::dispatch($this->client, $player);
//                }

        }
    }
}
