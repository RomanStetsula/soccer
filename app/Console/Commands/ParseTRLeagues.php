<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\League;

class ParseTRLeagues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:league';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse league from transfermarker';

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
//        $pageUrl = 'https://www.transfermarkt.co.uk/wettbewerbe/amerika?ajax=yw1&page=2';
//        $pageUrl = 'https://www.transfermarkt.co.uk/wettbewerbe/asien';
        $pageUrl = 'https://www.transfermarkt.co.uk/wettbewerbe/afrika';
        $rowSelector = '#yw1 tbody tr';
        $linkSelector = 'td a';

        $domTree = $this->getUrlParse($pageUrl);

        //get table rows
        $trs = $domTree->find($rowSelector);

        $limit_collect = collect($trs);
//        $limit_collect = collect($trs)->take(10);
        $limit = 6;
        $i=0;
        $limit_collect->each(function ($tr) use ($linkSelector, &$i, $limit){
            if(isset($tr->class)) {
                
                $link = $tr->find($linkSelector)[1];

                if(isset($link->href) && $i<$limit){
                    League::firstOrCreate(
                        ['url' => 'https://www.transfermarkt.co.uk'.$link->href],
                        ['name' => $link->title, 'country' => $tr->find('.flaggenrahmen')[0]->title]
                    );
                    $i++;
                }

            }
        });
        dd('finish...');

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
}
