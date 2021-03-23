<?php


namespace App\Traits;


use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

trait GuzzleTrait
{

    /**
     * @param null $site
     * @return \GuzzleHttp\Client
     */
    protected function getGuzzle()
    {
        return  new \GuzzleHttp\Client();
    }
}
