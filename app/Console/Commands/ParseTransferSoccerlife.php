<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\VirtualPlayer;

class ParseTransferSoccerlife extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:soccerlife';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing from sosserlife';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public $urls = 'http://www.soccerlife.ru/transfers.php';

    public $transfer_links_tag = '.min11';

//    public $team_squad_tag = '#champ_table a';
//
//    //player info tags
//    public $player_team_tag = '.team-0 tr a:nth-child(1)';
//
//    public $player_url_tag = 'a';
//
//
//    public $player_name_tag = '.stat-topline nobr';
//
//    public $team_tag = 'a.original2';
//
//    public $position = '.stat-topline nobr span';
//
//    public $birth_tag = 'td.taddy table table tr';
//
//    public $age = '';
//
//    public $nationality_tag = 'td.taddy table table tr:nth-child(4) .flags';
//
//    public $market_value = '';
//
//    public $player_team = '';
//
//    public $talent_tag = '.stat-topline .block_info';
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transfer_pages = $this->getTransferPages();
        
        foreach($transfer_pages as $page){
            $page_content = $this->getUrlParse('http://www.soccerlife.ru/team4.php');
            dd($page_content->plaintext);
            $all_tr_records = $page_content->find('a.original');
            dd($all_tr_records);
           
            foreach($all_tr_records as $record){
               printf( $record->src);
            }

        }

        foreach($this->urls as $lig_url){
            $team_squad_urls = $this->getTeamSquatLinks($lig_url);

            foreach($team_squad_urls as $url){

                $team_squad_page = $this->getUrlParse($url);

                $player_links = $this->getPlayerLink($team_squad_page);

                $this->getAndSavePlayer($player_links);
            }
            printf($lig_url);
            printf('<br>');
        }
        printf('finish');
    }

    /**
     * Getting Dom tree of page
     * @param $url
     * @return object
     */
    private function getUrlParse(){

        require_once('SimpleHtmlDom.php');

//        $urlParse = file_get_html($url);
//        
//        dd($urlParse);
//
//        if(!$urlParse){
//
//            $page_content = file_get_contents($url);
//            dd($page_content);
//
//            $urlParse = str_get_html($page_content);

//        $username='rstetsula';
//        $password='Q13m4qwe';

        $username = 'rstetsula';
        $password = 'Q13m4qwe';
        $loginUrl = 'https://www.soccerlife.ru/?backto=%2Findex.php';

//init curl
        $ch = curl_init();

//Set the URL to work with
        curl_setopt($ch, CURLOPT_URL, $loginUrl);

// ENABLE HTTP POST
        curl_setopt($ch, CURLOPT_POST, 1);

//Set the post parameters
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'user='.$username.'&pass='.$password);

//Handle cookies for the login
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
//not to print out the results of its query.
//Instead, it will return the results as a string return value
//from curl_exec() instead of the usual true/false.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

//execute the request (the login)
        $store = curl_exec($ch);

//the login is now done and you can continue to get the
//protected content.

//set the URL to the protected file
        curl_setopt($ch, CURLOPT_URL, 'http://www.soccerlife.ru/team4.php');

//execute the request
        $content = curl_exec($ch);

        curl_close($ch);

//save the data to disk
        $urlParse = str_get_html($content);
//        file_put_contents('~/download.zip', $content);

printf($urlParse->plaintext);
        dd('dsf');
        return $urlParse->find('body')->plaintext;
    }

    /**
     * @return array
     */
    private function getTransferPages()
    {
        $page_content = $this->getUrlParse('http://www.soccerlife.ru/transfers.php');

//        dd($page_content->plaintext);

        $transfer_links = $page_content->find($this->transfer_links_tag);

        $transfer_links[] = 'http://www.soccerlife.ru/transfers.php';

        return $transfer_links;
    }


    //get links of team roster
    private function getTeamSquatLinks($url){

        $page_content = $this->getUrlParse($url);

        $team_squad_links = $page_content->find($this->team_squad_tag);//$this->team_squad_tag
        $team_squad_links = array_filter($team_squad_links, function($team_link){
            if(strpos($team_link->attr['href'], 'roster.php') !== false){
                return $team_link;
            }
        });

        $team_squad_links = collect($team_squad_links)->map(function ($team) {
            return $team->attr['href'] = 'http://www.soccerlife.ru'.$team->attr['href'];
        })->toArray();

        return $team_squad_links;
    }

    /**
     *
     * getting links of players
     *
     * @param $team_squad_page
     * @return array
     */
    private function getPlayerLink($team_squad_page){

        $player_links = $team_squad_page->find($this->player_url_tag);

        $player_links = array_filter($player_links, function($team_link){
            if(strpos($team_link->attr['href'], 'player.php') !== false){
                return $team_link;
            }
        });

        $player_links = array_unique(collect($player_links)->map(function ($player) {
            return $player->attr['href'] = 'http://www.soccerlife.ru'.$player->attr['href'];
        })->toArray());

        return $player_links;
    }


    private function getAndSavePlayer($players_url){
        foreach($players_url as $url){
            $player_page = $this->getUrlParse($url);

            $player_name = $player_page->find($this->player_name_tag);

            $player_name = $player_name[0]->innertext;
            preg_match('/<\/span> \\t(.*) <sup>/', $player_name, $name);

            $player_name = explode(' ', $name[1]);
            $player['firstname'] = $player_name[1];
            $player['lastname'] = $player_name[0];

            $player['position'] = trim($player_page->find($this->position)[0]->plaintext);
            $str = '';
            $birth_date =  $player_page->find($this->birth_tag);
            foreach($birth_date as $date){
                $str .= $date->plaintext;
            }

            preg_match('/\((.*\/.*\/.{4})\)/', $str, $birth);

            if($birth){
                $player['birth_date'] = explode('/', $birth[1]);
                $player['birth_date'] = implode('-',array_reverse($player['birth_date']));
            } else {
                $player['birth_date'] = '1970-01-01';

            }

            $nationality = $player_page->find($this->nationality_tag)[0]->attr['title'];
            $player['nationality'] = trim(preg_replace('/\(.*\)/', '', $nationality));

            $player['team'] = $player_page->find($this->team_tag)[0]->plaintext;

            $player_parameters = explode('/', $player_page->find($this->talent_tag)[0]->plaintext);
            $player['age'] = intval($player_parameters[0]);
            $player['talent'] = intval($player_parameters[1]);
            $player['skill'] = intval($player_parameters[2]);

            $player['url'] = $url;

            $vitrualPlayer = VirtualPlayer::firstOrCreate($player);
            $vitrualPlayer->save();
        }
    }
}
