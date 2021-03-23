<?php


namespace App\Strategy\Parser;


class TypesSrbStrategy extends BaseParserStrategy
{

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

    public function run()
    {
        // TODO: Implement run() method.
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

    public function

}
