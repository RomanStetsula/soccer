<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\League;
use App\Models\TransfermarktPlayer;
use Carbon\Carbon;

class ParseTransfermartk extends Command
{
    /**
     * The name and signature of the console command.
     *kkkjjkhk
     * @var string
     */
    protected $signature = 'parse:transfermarkt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse transfermarket players';


    public $talent;

    public $team_name;

    public $team_squad_tag = '#yw1 .zentriert .vereinprofil_tooltip';

    public $player_url_tag = '#yw1 .spielprofil_tooltip';

    public $player_name_tag = '.dataName h1';

    public $player_data_tag = '.auflistung tbody tr';

    public $birth_tag = '.auflistung tr td a';

    public $market_value_tag = '.marktwertentwicklung .zeile-oben .right-td';

    public $team_name_tag = '.dataName h1';
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
        $leagues_to_parse = League::where('date_of_parsing', '<', Carbon::now()->subDays(30))
            ->orWhere('date_of_parsing', null)
            ->get();

        foreach($leagues_to_parse as $league){

            $this->talent = $league->base_talent;

            $team_squad_urls = $this->getTeamSquatLinks($league->url);

            foreach($team_squad_urls as $url){

                $team_squad_page = $this->getUrlParse($url);
                
                //players url on site
                $player_links = $this->getPlayerLink($team_squad_page);

                $team_name = trim($team_squad_page->find($this->team_name_tag)[0]->plaintext);

                //parsed players url
                $parsed_team_players_urls = TransfermarktPlayer::where('team', $team_name)->select('url')->get();
                $parsed_team_players_urls = $parsed_team_players_urls->map(function ($item) {
                    return $item->url;
                });

                $player_links_to_parse = collect($player_links)->diff($parsed_team_players_urls)->all();
                
                if($parsed_team_players_urls->isNotEmpty()){

                    $decamp_players = $parsed_team_players_urls->diff($player_links)->all();
                    
                    $this->setTalentToOne($decamp_players);
                }

                $this->team_name = $team_name;

                $this->getAndSavePlayer($player_links_to_parse);
            }

            $league->date_of_parsing = date('Y-m-d');

            $league->save();
        }

    }

    /**
     * Getting Dom tree of page
     *
     * @param $url
     * @return object
     */
    private function getUrlParse($url)
    {

        require_once('SimpleHtmlDom.php');

        $urlParse = file_get_html($url);

        if(!$urlParse){
            $curl_handle=curl_init();
            curl_setopt($curl_handle, CURLOPT_URL, $url);
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'soccer.app');
            $page_content = curl_exec($curl_handle);
            curl_close($curl_handle);

            $urlParse = str_get_html($page_content);
        }
        return $urlParse;
    }

    //get links of team roster
    private function getTeamSquatLinks($url)
    {

        $page_content = $this->getUrlParse($url);

        $team_squad_links = $page_content->find($this->team_squad_tag);

        $team_squad_links = collect($team_squad_links)->map(function ($team) {
            return $team->attr['href'] = 'https://www.transfermarkt.co.uk'.$team->attr['href'];
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
    private function getPlayerLink($team_squad_page)
    {

        $player_links = $team_squad_page->find($this->player_url_tag); //$this->player_url_tag

        $player_links = array_unique(collect($player_links)->map(function ($player) {
            return $player->attr['href'] = 'https://www.transfermarkt.co.uk'.$player->attr['href'];
        })->toArray());

        return $player_links;
    }

    /**
     * @param $players_url
     */
    private function getAndSavePlayer($players_url)
    {
        foreach($players_url as $url){
//            dd($url);
            $player_page = $this->getUrlParse($url);

            //player name
            $player_name = $player_page->find($this->player_name_tag);
            if(array_key_exists(0, $player_name)){
                $player_name = $player_name[0]->plaintext;
                $player_name = explode(' ', $player_name);
                $player['firstname'] = $player_name[0]??$player_name;
                $player['lastname'] = $player_name[1]??'';
            } else {
                continue;
            }

            //get player data
            $player_data = $player_page->find($this->player_data_tag);

            //player birth date
            $birth_date = $player_page->find($this->birth_tag);

            if(array_key_exists(0, $birth_date)) {
                $birth_date = explode('/', $birth_date[0]->attr['href']);
                if (is_array($birth_date) && preg_match('/\d\d\d\d-\d\d-\d\d/', end($birth_date))) {
                    $player['birth_date'] = end($birth_date);
                }
            }

            foreach($player_data as $data){
                $element_text = $data->plaintext;

                //nationality
                if(strpos($element_text, 'Nationality:')){
                    $player['nationality'] = trim(str_replace('Nationality:', '', $element_text));
                    $player['nationality'] = str_replace(['&nbsp;', ' '], [' ', ''], $player['nationality']);
                    continue;
                }

                if(strpos($element_text, 'Position:')){
                    $player['position'] = trim(str_replace('Position:', '', $element_text));
                    $player['position'] = trim($player['position'], "&nbsp;");
                    continue;
                }

                if(strpos($element_text, 'Age:')){
                    $player['age'] = trim(str_replace('Age:', '', $element_text));
                    $player['age'] = intval(trim($player['age'], "&nbsp;"));
                    continue;
                }
            }

            $player['team'] = $this->team_name;

            $player_value = $player_page->find($this->market_value_tag);

            if(isset($player_value[0])){
                $player['market_value'] = trim($player_value[0]->plaintext)??'';
            }

            $player['talent'] = $this->talent;

            $player['url'] = $url;

            $transfermarktPlayer =  TransfermarktPlayer::updateOrCreate(
                ['url' => $url],
                [
                    'firstname'=>$player['firstname'],
                    'lastname'=>$player['lastname'],
                    'birth_date'=>$player['birth_date'],
                    'nationality' => $player['nationality'],
                    'position' => $player['position'],
                    'age' => $player['age'],
                    'team' => $player['team'],
                    'market_value' => $player['market_value']??'',
                    'talent' => $player['talent']
                ]
            );

        }
    }

    /**
     * @param $decamp_players
     */
    private function setTalentToOne($decamp_players)
    {
        $transfermarktPlayers = TransfermarktPlayer::whereIn('url', $decamp_players)
            ->get();

        foreach($transfermarktPlayers as $player){
            $player->talent = 1;
            $player->team = 'unknown';
            $player->save();
        }
    }

}
