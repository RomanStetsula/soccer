<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SoccerlifePlayerOnTR;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;

class ParsePlayersOntransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:on-transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var string
     */
    public $transfer_links_tag = '.min11';

    /**
     * @var string
     */
    public $players_on_transfer_tag = '.taddy tr';

    /**
     * @var string
     */
    public $player_birth_tag = 'td.bt';

    /**
     * @var SoccerlifePlayerOnTR
     */
    public $player;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SoccerlifePlayerOnTR $player)
    {
        parent::__construct();
        $this->player = $player;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.soccerlife.ru/transfers.php');

        $form = $crawler->selectButton('Войти')->form();

        $crawler = $client->submit($form, array('username' => 'wot_t', 'password' => '1989st'));

        $crawler = $client->request('GET', 'http://www.soccerlife.ru/transfers.php');

        $pages = trim($crawler->filter($this->transfer_links_tag)->last()->text());
        dd($pages);

        for ($i = 0; $i< $pages; $i++){

            $parsed_page = $client->request('GET', 'http://www.soccerlife.ru/transfers.php?page='.$i);

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
                $transfer_date = trim($player->children()->eq(8)->text());
                $player_on_tr['transfer_date'] = $this->getDateTime($transfer_date);
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
                $old_player = SoccerlifePlayerOnTR::where('url', $player['url'])->first();

                if($old_player){
                    //update player
                    $old_player->fill([
                                    'transfer_value' =>  $player['transfer_value'],
                                    'transfer_date' => $player["transfer_date"],
                                    'offers' => $player["offers"]
                                    ]);
                    $old_player->save();
                } else {
                    $this->getAndSavePlayer($client, $player);
                }

            }
        }//
        dd('finish');
    }

    /**
     * @param $players_url
     */
    private function getAndSavePlayer($client, $player)
    {
        //wait few seconds before parse player
        sleep(rand(5, 10)/1.25);
        $player_page = $client->request('GET', $player['url']);

        // check is player on transfermarket save him or skip
        $pl = $player_page->filter('.tm_url')->each(function (Crawler $node, $i) {
            return $node->text();
        });

        if(!empty($pl)) {

            $player_name = explode(' ', $player['fullname']);
            $player['firstname'] = $player_name[1];
            $player['lastname'] = $player_name[0];


            $birth_date = $player_page->filter($this->player_birth_tag)->each(function (Crawler $node, $i) {
                return $node->text();

            });
            $str = '';
            foreach ($birth_date as $date) {
                $str .= $date;
            }
            preg_match('/\((.*\/.*\/.{4})\)/', $str, $birth);

            if ($birth) {
                $player['birth_date'] = explode('/', $birth[1]);
                $player['birth_date'] = implode('-', array_reverse($player['birth_date']));
            } else {
                $player['birth_date'] = '1970-01-01';

            }

            $player['nationality'] = $player_page->filter('.stat-topline .flags')->attr('title');
            $player['nationality'] = trim(preg_replace('/\(.*\)/', '', $player['nationality']));

            unset($player['fullname']);
            SoccerlifePlayerOnTR::create($player);
        }
    }

    /**
     * make a date time string
     *
     * @param $string
     * @return string
     */
    private function getDateTime($string)
    {
        if(strpos($string,'Завтра') !== false){
            $string = str_replace('Завтра в', 'tomorrow', $string);
            $date_time = Carbon::parse($string);
        } elseif (strpos($string,'Сегодня') !== false){
            $string = str_replace('Сегодня в', 'today', $string);
            $date_time = Carbon::parse($string);
        } else {
            $date_time = Carbon::parse($string);
        }

        return $date_time->toDateTimeString();
    }

}
