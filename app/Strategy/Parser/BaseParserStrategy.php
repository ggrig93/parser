<?php


namespace App\Strategy\Parser;


use App\Interfaces\ParserStrategyInterface;
use App\Models\Site;
use App\Models\Size;
use App\Traits\CrawlerTrait;
use App\Traits\GuzzleTrait;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

abstract class BaseParserStrategy implements ParserStrategyInterface
{
    use CrawlerTrait;
    use GuzzleTrait;

    protected  $url;

    protected $site;

    protected $sizes;

    protected $types;

    protected $productSelector;

    protected $paginatorSelector;

    protected $shineFilter;

    protected $filterInputNames;

    protected $activeSize;

    /**
     * BaseParserStrategy constructor.
     */
    public function __construct()
    {
        $this->setSite();
    }

    /**
     *
     */
    public function setSite()
    {
        $this->site =  Site::query()
            ->where('name', static::SITE)
            ->first();
    }

    /**
     * @return array
     */
   public function getSizes()
   {
      return  $this->sizes = Size::query()->get()->toArray();
   }


    /**
     *
     */
    public function getTires()
    {
        $crawler = $this->getCrawler();

        $this->saveTires( $this->getPageTires($crawler));

        $this->paginateTires($crawler);
    }

    /**
     * @param $crawler
     * @return bool
     */
    public function paginateTires($crawler)
    {
        $client = $this->getCrawlerClient();

        if($crawler->filter($this->paginatorSelector)->count()) {
            $currentPage = $crawler->filter($this->paginatorSelector)->first()
                ->filter('.active')->first()->text();

            $nextPage = $crawler->filter($this->paginatorSelector)->first()
                ->selectLink($currentPage)->nextAll()->first();

            if ($nextPage->count()) {
                $link = $crawler->filter($this->paginatorSelector)->first()
                    ->selectLink($nextPage->text())->link();

                $crawler = $client->click($link);
                $this->saveTires($this->getPageTires($crawler));
                $this->paginateTires($crawler);

            }
        }
        return true;
    }

    /**
     * @param $sizes
     * @return array
     */
    protected function setUrlSizes($sizes)
    {
        $form = [
            $this->filterInputNames['width']    =>  $sizes['width'] ,
            $this->filterInputNames['profile']  =>  $sizes['profile'],
            $this->filterInputNames['diameter'] => $this->diameterStartPrefix. $sizes['diameter'],
        ];

        return $form;
    }

//    public abstract function saveTires($tires);
    /**
     * @param $tires
     */
    public function saveTires($tires)
    {
        try {
            $this->site->shines()->createMany($tires);

        } catch (\Exception $e) {
            dd($e);
        }

    }
}

