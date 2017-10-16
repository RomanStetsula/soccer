<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;
use App\SoccerlifePlayerOnTR;

class ParseCreateUpdatePlayer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $player_birth_tag = 'td.bt';
    /**
     * @var
     */
    public $client;

    /**
     * @var
     */
    public $player;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client, $player)
    {
        $this->client = $client;
        $this->player = $player;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $player_page = $this->client->request('GET', $this->player['url']);

        // check is player on transfermarket save him or skip
        $pl = $player_page->filter('.tm_url')->each(function (Crawler $node, $i) {
            return $node->text();
        });

        if(!empty($pl)) {

            $player_name = explode(' ', $this->player['fullname']);
            $this->player['firstname'] = $player_name[1];
            $this->player['lastname'] = $player_name[0];


            $birth_date = $player_page->filter($this->player_birth_tag)->each(function (Crawler $node, $i) {
                return $node->text();

            });
            $str = '';
            foreach ($birth_date as $date) {
                $str .= $date;
            }
            preg_match('/\((.*\/.*\/.{4})\)/', $str, $birth);

            if ($birth) {
                $birth_date = explode('/', $birth[1]);
                $this->player['birth_date'] = implode('-', array_reverse($birth_date));
            } else {
                $this->player['birth_date'] = '1970-01-01';
            }

            $nationality = $player_page->filter('.stat-topline .flags')->attr('title');
            $this->player['nationality'] = trim(preg_replace('/\(.*\)/', '', $nationality));

            unset($this->player['fullname']);
            SoccerlifePlayerOnTR::create($this->player);
        }

    }
}
