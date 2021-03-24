<?php


namespace App\Strategy\Parser;


use Illuminate\Support\Facades\Http;

class TyrangoStrategy extends BaseParserStrategy
{
    const SITE = "turango.ru/tires";

    protected  $url = "https://www.turango.ru/tires/cars/search.php?searchtype=tire&tirewidth=185&tireheight=65&tireradius=15&tiremark=2737&tirewinter=0&tiresummer=1&tireallseason=1";

    protected $productSelector = '.wrap-ext';

    protected $paginatorSelector = '.result-block-wrapper nav#paginator';

    protected $shineFilter = 'select[name="season"]';

//    protected $seasonFilters = [90, 92];

    protected $filterInputNames = [
        'group' =>  'season',
        'width' =>  'tirewidth',
        'profile' =>  'tireheight',
        'brand' =>  '2737',
        'diameter' =>  'tireradius',
    ];

    const AVAILABILITY_URL = "https://tyres.spb.ru/catalog/ajax-get-storage";


    public function run()
    {
        // TODO: Implement run() method.
        $crawler = $this->getCrawler();

        $this->getSizes();

        foreach ($this->sizes as $size) {
            $sizes = $this->urlSizes($size);

            $this->activeSize = $size;
            $this->getTires();
        }

    }

    /**
     * @param $sizes
     * @return array|string
     */
    public function urlSizes($sizes)
    {
        return "https://www.turango.ru/tires/cars/search.php?searchtype=tire
        &tirewidth=".$sizes['width'].
        "&tireheight=".$sizes['profile'].
        "&tireradius=".$sizes['diameter'].
        "&tiremark=2737".
        "&tirewinter=0".
        "&tiresummer=1".
        "&tireallseason=1";

    }

    /**N
     * @param $crawler
     * @return array
     */
    public function getPageTires($crawler)
    {
        $pageVals = [];
        $client = $this->getCrawlerClient();

        $crawler->filter('.search > table.catalog-property')->filter('tr')->each(function ($node) use (&$pageVals, $client) {
            if(!$node->filter('table')->count()) {
                return;
            }
            $link =  $node->filter('.name-profil-tires-link a')->link();
            $tirePage = $client->click($link);

            $text = $this->getAvailability($tirePage);

            $pageVals[] = [
                "site_id"      => $this->site['id'],
                "size_id"      => $this->activeSize['id'],
                "name"         => $node->filter('.name-profil-tires-link')->text(),
                "availability" => $text
            ];
        });

        return $pageVals;
    }

    public function getAvailability($tirePage)
    {
        try {
          $text =  $tirePage->filter('colgroup')->last()
              ->nextAll()->last()
              ->filter('td')->last()->text();

          return $text;
        } catch (\Exception $e) {
            dd("turango.ru/tires product page count not found". $e->getMessage());
        }
    }

}
