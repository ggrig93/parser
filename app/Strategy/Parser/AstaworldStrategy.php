<?php


namespace App\Strategy\Parser;


use App\Models\Size;

class AstaworldStrategy extends BaseParserStrategy
{

    const SITE = "astaworld.ru/tyres";

    protected  $url = "https://www.astaworld.ru/tyres?availability=0&group%5B%5D=90&set%5B%5D=0&b%5B%5D=185&h%5B%5D=65&d%5B%5D=R15&brand%5B%5D=MICHELIN";
//    protected $url = "https://www.astaworld.ru/tyres?availability=0&group%5B%5D=90&set%5B%5D=4&b%5B%5D=&h%5B%5D=&d%5B%5D=&brand%5B%5D=";

    protected $productSelector = '.wrap-ext';

    protected $paginatorSelector = '.result-block-wrapper nav#paginator';

    protected $shineFilter = 'span[data-name="groups"]';

    protected $seasonFilters = [90, 92];

    protected $filterInputNames = [
      'group' =>  'group',
      'width' =>  'b',
      'profile' =>  'h',
      'brand' =>  'brand',
      'diameter' =>  'd',
    ];

    protected $diameterStartPrefix = 'R';

    /**
     *
     */
    public function run()
    {
        // TODO: Implement run() method.
        $crawler = $this->getCrawler();
        $this->getSizes();

        $shineFilter = $crawler->filter($this->shineFilter)->filter('input')->each(function ($node, $key) use ($crawler) {
            $filter = $node->attr('value');
            if(!in_array( $filter, $this->seasonFilters)) {
              return;
            }
            $this->url = modify_url_query($crawler->getUri(), [$this->filterInputNames['group'] => [$filter] ]);

            foreach ($this->sizes as $size) {
                $sizes = $this->setUrlSizes($size);

                $this->url = modify_url_query($this->url, $sizes);
                $this->activeSize = $size;
                $this->getTires();

            }

        });
    }

    /**
     * @param $crawler
     * @return array
     */
    public function getPageTires($crawler)
    {
        $pageVals = [];
        $crawler->filter('.wrap-ext')->each(function ($node) use (&$pageVals) {
            $pageVals[] = [
                "site_id"      => $this->site['id'],
                "size_id"      => $this->activeSize['id'],
                "name"         => $node->filter('.title')->text(),
                "availability" => $node->filter('span.nowrap acronym')->last()->text()
            ];
        });

        return $pageVals;
    }

    /**
     * @param $size
     * @return array
     */
    protected function setUrlSizes($size)
    {
        $form = [
            $this->filterInputNames['width'] => [ $size['width'] ],
            $this->filterInputNames['profile'] => [ $size['profile'] ],
            $this->filterInputNames['diameter'] => [$this->diameterStartPrefix. $size['diameter'] ],
        ];

        return $form;
    }


}
