<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RealPlayer;

class RealPlayerParserController extends Controller
{
    public $url = 'http://www.msfl.cz/';

    public $team_aquad_tag = '.polozka a';

    //player info tags
    public $player_team_tag = '.klub h2';

    public $player_url = '.soupiska a';

    public $player_name = '.person-info h1';

    public $team = '.soupiska img';

    public $position = '.person-info .person-post';

    public $birth = '';

    public $age = '';

    public $nationality = '.person-info img';

    public $market_value = '';

    public $player_team = '';

    public $leagve_base_talent = 3;


    public function parse(){

        $team_squad_urls = $this->getTeamSquatLinks($this->url);

        foreach($team_squad_urls as $url){
            $team_squad_page = $this->getUrlParse($url);

            //get player link
            $player_links = $this->getPlayerLink($team_squad_page);

            $this->getAndSavePlayer($player_links);
        }
        dd('finish');
    }

    /**
     * Getting Dom tree of page
     * @param $url
     * @return object
     */
    private function getUrlParse($url){

        require_once('SimpleHtmlDom.php');

        $urlParse = file_get_html($url);

        if(!$urlParse){

            if(get_headers($url, 1)[0] != 'HTTP/1.1 200 OK'){
                return false;
            }

            $page_content = file_get_contents($url);

            $urlParse = str_get_html($page_content);

        };
        return $urlParse;
    }

    //only for mslf site
    private function getTeamSquatLinks($url){

        $page_content = $this->getUrlParse($url);

        $team_squad_links = $page_content->find($this->team_aquad_tag);

        $team_squad_links = collect($team_squad_links)->map(function ($team) {
            return $team->attr['href'] = $this->url.$team->attr['href'].'/hraci';
        })->toArray();

        return $team_squad_links;
    }

    /**
     * @param $team_squad_page
     * @return array
     */
    private function getPlayerLink($team_squad_page){

        $player_links = $team_squad_page->find($this->player_url);

        $team = $team_squad_page->find($this->player_team_tag);
        $this->player_team = $team[0]->plaintext;

        $player_links = collect($player_links)->map(function ($link) {
            return $link->attr['href'] = str_replace_last('//', '/', $this->url.$link->attr['href']);
        })->toArray();

        return $player_links;
    }


    private function getAndSavePlayer($players_url){
        foreach($players_url as $url){
            $player_page = $this->getUrlParse($url);

            $player_name = $player_page->find($this->player_name)[0]->plaintext;

            $player_name = explode(' ', $player_name);
            $player['firstname'] = $player_name[0];
            $player['lastname'] = $player_name[1];

            $player['position'] = $player_page->find($this->position)[0]->plaintext;

            preg_match('/\d\d. \d\d. \d{4}/', $player_page->find('.person-info')[0]->plaintext, $matches);

            $player['birth_date'] = explode('. ', $matches[0]);
            $player['birth_date'] = implode('-',array_reverse($player['birth_date']));

            $player['nationality'] = $player_page->find($this->nationality)[0]->attr['alt'];

            $player['team'] = $this->player_team;

            $player['leagve_base_talent'] = $this->leagve_base_talent;

            $player['url'] = $url;

            $realPlayer = RealPlayer::firstOrCreate($player);
            $realPlayer->save();
        }
    }

}
