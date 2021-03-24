<?php

namespace App\Http\Controllers;

use App\Services\ParserRunService;
use App\Strategy\Parser\AstaworldStrategy;
use App\Strategy\Parser\TypesSrbStrategy;
use App\Strategy\Parser\TyrangoStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ParserController extends Controller
{


    /**
     * @var array
     */
    private $sites = [
        "astaworld.ru/tyres" => AstaworldStrategy::class,
        "tyres.spb.ru" => TypesSrbStrategy::class,
        "turango.ru/tires" => TyrangoStrategy::class,
//        "autopartner-perm.ru/" => AstaworldStrategy::class,
//        "vianor54.ru" => AstaworldStrategy::class,
//        "mirkoles-nk.ru/" => AstaworldStrategy::class,
//        "24kolesa.ru" => AstaworldStrategy::class,
//        "sparewheel.ru/" => AstaworldStrategy::class,
//        "sochityre.ru/" => AstaworldStrategy::class,
//        "pin-avto.ru/" => AstaworldStrategy::class,
    ];


    public function index(Request $request)
    {
        $site = $request->site;

        if($site && Arr::get( $this->sites, $site )) {
            $className = $this->sites[$site];
            $parserService =  new ParserRunService(new $className());
            $parserService->run();

        }  else {
            collect($this->sites)->map(function ($item) {
                $siteStrategy = new $item();
                $parserService =  new ParserRunService($siteStrategy);
                $parserService->run();
            });
        }


    }

}
