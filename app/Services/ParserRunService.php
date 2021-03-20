<?php


namespace App\Services;


use App\Interfaces\ParserStrategyInterface;
use App\Services\Parser\AstaworldStrategy;
use Illuminate\Support\Arr;

class ParserRunService
{
    protected $strategy;

    /**
     * @var array
     */
    private $sites = [
        "astaworld.ru/tyres" => AstaworldStrategy::class,
//        "htyres.spb.ru/" => AstaworldStrategy::class,
//        "turango.ru/tires/" => AstaworldStrategy::class,
//        "autopartner-perm.ru/" => AstaworldStrategy::class,
//        "vianor54.ru" => AstaworldStrategy::class,
//        "mirkoles-nk.ru/" => AstaworldStrategy::class,
//        "24kolesa.ru" => AstaworldStrategy::class,
//        "sparewheel.ru/" => AstaworldStrategy::class,
//        "sochityre.ru/" => AstaworldStrategy::class,
//        "pin-avto.ru/" => AstaworldStrategy::class,
    ];

    public function __construct(ParserStrategyInterface $parserStrategy)
    {
        $this->strategy = $parserStrategy;
    }

    /**
     *
     */
    public function run()
    {
      return  $this->strategy->run();
    }
}
