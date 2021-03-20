<?php


namespace App\Strategy\Parser;


use App\Interfaces\ParserStrategyInterface;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

abstract class BaseParserStrategy implements ParserStrategyInterface
{

    protected  $url;

    protected $sizes;

    const SIZES = [
        '185/65/15',
        '195/65/15',
        '205/55/16',
        '215/65/16',
        '205/60/16',
        '215/60/16',
        '215/55/17',
        '225/65/17',
        '235/65/17',
        '225/55/17',
        '235/55/17',
        '225/60/17',
        '215/60/17',
        '225/50/17',
        '215/65/17',
        '235/60/18',
        '265/60/18',
        '245/45/18',
        '235/45/18',
        '235/55/18',
        '225/60/18',
        '255/55/18',
        '245/40/18',
        '225/55/18',
        '225/45/18',
        '245/50/18',
        '255/50/19',
        '235/55/19',
        '255/55/19',
        '245/45/19',
        '225/55/19',
        '245/50/19',
        '285/50/20',
        '275/40/20',
        '315/35/20',
        '235/55/20',
        '295/40/21',
        '275/45/21',
        '275/50/21',
        '275/40/22',

    ];

    protected $types;

    protected $productSelector;

    protected $paginatorSelector;
    /**
     * @return \Symfony\Component\DomCrawler\Crawler
     */
   protected function getCrawler()
   {
       $client = new Client(HttpClient::create(['timeout' => 60]));
       $crawler = $client->request('GET', $this->url);

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

    /**
     * @return array
     */
   public function getSizes()
   {
       $newSizes =  [];

        foreach (self::SIZES as $size) {
           $sizes = explode('/' , $size);
            $newSizes[] = [
                'width'    => $sizes[0],
                'profile'  => $sizes[1],
                'diameter' => $sizes[2],
            ];
        }
        $this->sizes = $newSizes;

      return $newSizes;
   }

}

