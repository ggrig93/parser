<?php


namespace App\Strategy\Parser;


use Illuminate\Support\Facades\Http;

class TypesSrbStrategy extends BaseParserStrategy
{
    const SITE = "tyres.spb.ru";

    protected  $url = "https://tyres.spb.ru/catalog_tires_level_search_d_15_w_195_h_65_season_0_brand_michelin_custom_0";

    protected $productSelector = '.wrap-ext';

    protected $paginatorSelector = '.result-block-wrapper nav#paginator';

    protected $shineFilter = 'select[name="season"]';

    protected $seasonFilters = [0, 2];

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

        foreach ($this->seasonFilters as $val) {

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
            ."_brand_michelin"
            ."_season_".$sizes['season']
            ."_custom_0";
    }

    /**
     * @param $crawler
     * @return array
     */
    public function getPageTires($crawler)
    {
        $pageVals = [];

        $crawler->filter('.result .card')->each(function ($node) use (&$pageVals) {
            $pageVals[] = [
                "site_id"      => $this->site['id'],
                "size_id"      => $this->activeSize['id'],
                "name"         => $node->filter('.card-title')->text(),
                "availability" => 4
            ];
        });

        return $pageVals;
    }


}
