<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\SoccerlifePlayerOnTR;
use App\Jobs\ParseTransferPage;

class ParsePlayerOnTransfer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var string
     */
    public $transfer_links_tag = '.min11';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.soccerlife.ru/transfers.php');

        $form = $crawler->selectButton('Войти')->form();

        $crawler = $client->submit($form, array('username' => 'rstetsula', 'password' => 'Q13m4qwe'));

        $crawler = $client->request('GET', 'http://www.soccerlife.ru/transfers.php');

        $pages = trim($crawler->filter($this->transfer_links_tag)->last()->text());

        for ($i = 0; $i< $pages; $i++){

            ParseTransferPage::dispatch($client, $i);

        }
    }
}
