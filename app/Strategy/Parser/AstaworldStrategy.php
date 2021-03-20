<?php


namespace App\Strategy\Parser;


class AstaworldStrategy extends BaseParserStrategy
{

//    protected  $url = "https://www.astaworld.ru/tyres?availability=0&group%5B%5D=90&set%5B%5D=0&b%5B%5D=185&h%5B%5D=65&d%5B%5D=R15&brand%5B%5D=MICHELIN";
    protected  $url = "https://www.astaworld.ru/tyres?availability=0&group%5B%5D=90&set%5B%5D=4&b%5B%5D=&h%5B%5D=&d%5B%5D=&brand%5B%5D=";

    protected $productSelector = '.wrap-ext';

    protected $paginatorSelector  = '.result-block-wrapper nav#paginator';

    public function run()
    {
        // TODO: Implement run() method.

//        try {
            $sizes = $this->getSizes();
            $sizes = $this->getTires();
//        } catch (\Exception $e) {
//            dd($e);
//        }

    }

    public function getTires()
    {
        $crawler = $this->getCrawler();
        $client = $this->getCrawlerClient();
        $values = [];

dd($crawler->filter($this->paginatorSelector)->first()->selectLink('443')->nextAll()->first()->count());

       $link =  $crawler->filter($this->paginatorSelector)->first()->selectLink('2')->link();

        $crawler = $client->click($link);

        dd($crawler->filter($this->paginatorSelector)->first());
        $crawler->filter('.wrap-ext')->each(function ($node) use (&$values) {
            $values[] = [
                "name" =>  $node->filter('.title')->text(),
                "count" => $node->filter('span.nowrap acronym')->last()->text()
            ];

        });
    dd($values);
    }




}
