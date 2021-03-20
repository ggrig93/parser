<?php


namespace App\Strategy\Parser;


class AstaworldStrategy extends BaseParserStrategy
{

//    protected  $url = "https://www.astaworld.ru/tyres?availability=0&group%5B%5D=90&set%5B%5D=0&b%5B%5D=185&h%5B%5D=65&d%5B%5D=R15&brand%5B%5D=MICHELIN";
    protected $url = "https://www.astaworld.ru/tyres?availability=0&group%5B%5D=90&set%5B%5D=4&b%5B%5D=&h%5B%5D=&d%5B%5D=&brand%5B%5D=";

    protected $productSelector = '.wrap-ext';

    protected $paginatorSelector = '.result-block-wrapper nav#paginator';

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


        $values[] = $this->getPageTires($crawler);

        $this->paginateTires($crawler, $values);
        dd($values);
    }

    public function paginateTires($crawler,  $values)
    {
        $client = $this->getCrawlerClient();

        $nextPage = $crawler->filter($this->paginatorSelector)
            ->first()->selectLink('1')->nextAll()->first();

        if ($nextPage->count()) {
            $link = $crawler->filter($this->paginatorSelector)->first()
                ->selectLink($nextPage->text())->link();

            $crawler = $client->click($link);

            $values[] = $this->getPageTires($crawler);

            if(intval($nextPage->text()) < 5) {
                $this->paginateTires($crawler, $values);
            }
        }
        return $values;
    }

    public function getPageTires($crawler)
    {
        $pageVals = [];
        $crawler->filter('.wrap-ext')->each(function ($node) use (&$values) {
            $values[] = [
                "name" => $node->filter('.title')->text(),
                "count" => $node->filter('span.nowrap acronym')->last()->text()
            ];

        });

        return $pageVals;
    }
}
