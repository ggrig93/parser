<?php


namespace App\Traits;


use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

trait CrawlerTrait
{

    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getCrawler($site = null)
    {
        $site = $site ?? $this->url;
        $client = new Client(HttpClient::create(['timeout' => 60]));
        $crawler = $client->request('GET', $site);

        return $crawler;
    }


    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getCrawlerClient()
    {
        $client = new Client(HttpClient::create(['timeout' => 60]));

        return $client;
    }

    public function formSubmit( $form, $val)
    {
        $client = $this->getCrawlerClient();

        $crawler = $client->submit($form, $val);
        dd($crawler);
        $crawler->filter('.flash-error')->each(function ($node) {
            print $node->text()."\n";
        });

        return $crawler;
    }
}
