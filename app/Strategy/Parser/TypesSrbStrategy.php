<?php


namespace App\Strategy\Parser;


use Illuminate\Support\Facades\Http;

class TypesSrbStrategy extends BaseParserStrategy
{
    const SITE = "tyres.spb.ru";

    protected  $url = "https://tyres.spb.ru/catalog_tires_level_search_d_15_w_195_h_65_season_0_brand_michelin";

    protected $productSelector = '.wrap-ext';

    protected $paginatorSelector = '.result-block-wrapper nav#paginator';

    protected $shineFilter = 'select[name="season"]';

    protected $filters = [0, 1, 2];

    protected $filterInputNames = [
        'group' =>  'season',
        'width' =>  'w',
        'profile' =>  'h',
        'brand' =>  'brand',
        'diameter' =>  'd',
    ];

    const AVAILABILITY_URL = "https://tyres.spb.ru/catalog/ajax-get-storage";


    public function run()
    {
        // TODO: Implement run() method.
        $this->setCountry();
        $crawler = $this->getCrawler();

        $this->getSizes();

        foreach ($this->filters as $val) {

            foreach ($this->sizes as $size) {
                $size['season'] = $val;
                $sizes = $this->urlSizes($size);

                $this->activeSize = $size;
                $this->getTires();
            }
        }
    }

    public function setCountry()
    {
       $setLangUrl =  'https://tyres.spb.ru/sidebar/set-town?town=%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B0';
       $this->getCrawler($setLangUrl);
    }

    /**
     * @param $sizes
     * @return array|string
     */
    public function urlSizes($sizes)
    {
        return "https://tyres.spb.ru/catalog_tires_level_search_d_".$sizes['diameter']
            ."_w_".$sizes['width']
            ."_h_".$sizes['profile']
            ."_season_".$sizes['season']
            ."_brand_michelin";
    }

    /**
     * @param $crawler
     * @return array
     */
    public function getPageTires($crawler)
    {
        $pageVals = [];

        $token = $crawler->filter('meta[name="csrf-token"]')->first()->attr('content');

        $crawler->filter('.result .card')->each(function ($node) use (&$pageVals, $token) {
            $id = $node->attr('data-id');
            $this->getAvailability($id, $token);
            $res =   $node->click(selectLink('Уточнить наличие')->link());

            $pageVals[] = [
                "site_id"      => $this->site['id'],
                "size_id"      => $this->activeSize['id'],
                "name"         => $node->filter('.card-title')->text(),
                "availability" => $node->filter('#store-tab-content #day')->last()->text()
            ];
        });

        return $pageVals;
    }

    public function getAvailability($id, $token)
    {
        $client = $this->getCrawlerClient();
        $guzzle = $this->getGuzzle();

        try {
//            $response = $guzzle->request('POST', self::AVAILABILITY_URL,  [
//                "headers" => [
//                    'Accept'     => 'application/json',
//                    'X-CSRF-Token'     => $token,
//                ],
//                "form_params" => [
//                    'type' => 'tyres',
//                    'id' => $id,
////                    '_token' => $token,
//                ],
//
//            ]);
        } catch (\Exception $exception) {
            dd($exception);
        }

//dd($response);
    }
    public function saveTires($tires)
    {

    }
}
