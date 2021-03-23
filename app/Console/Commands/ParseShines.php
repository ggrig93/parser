<?php

namespace App\Console\Commands;

use App\Services\ParserRunService;
use App\Strategy\Parser\AstaworldStrategy;
use App\Strategy\Parser\TypesSrbStrategy;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ParseShines extends Command
{

    /**
     * @var array
     */
    private $sites = [
        "astaworld.ru/tyres" => AstaworldStrategy::class,
        "tyres.spb.ru" => TypesSrbStrategy::class,
//        "turango.ru/tires/" => AstaworldStrategy::class,
//        "autopartner-perm.ru/" => AstaworldStrategy::class,
//        "vianor54.ru" => AstaworldStrategy::class,
//        "mirkoles-nk.ru/" => AstaworldStrategy::class,
//        "24kolesa.ru" => AstaworldStrategy::class,
//        "sparewheel.ru/" => AstaworldStrategy::class,
//        "sochityre.ru/" => AstaworldStrategy::class,
//        "pin-avto.ru/" => AstaworldStrategy::class,
    ];


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:shines  {site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse shines from site or multiple sites';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function handle()
    {
        $site = $this->argument('site');
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
