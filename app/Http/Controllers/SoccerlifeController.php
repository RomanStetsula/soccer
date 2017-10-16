<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\SoccerlifePlayerOnTR;

class SoccerlifeController extends Controller
{
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
     * SoccerlifeController constructor.
     * @param SoccerlifePlayerOnTR $player
     */
    public function __construct(SoccerlifePlayerOnTR $player)
    {
        $this->player = $player;
    }

    /**
     *
     */
    public function parse()
    {

        $client = new Client();
        $crawler = $client->request('GET', 'http://www.soccerlife.ru/transfers.php');

        $form = $crawler->selectButton('Войти')->form();

        $crawler = $client->submit($form, array('username' => 'rstetsula', 'password' => 'Q13m4qwe'));

        $crawler = $client->request('GET', 'http://www.soccerlife.ru/transfers.php');

        $pages = trim($crawler->filter($this->transfer_links_tag)->last()->text());

        for ($i = 100; $i< $pages; $i++){
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
                    $this->getAndSavePlayer($client, $player);
//                }

            }

            dd('finish');

        }

    }

    /**
     * @param $players_url
     */
    private function getAndSavePlayer($client, $player){
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
}
