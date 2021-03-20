<?php


namespace App\Strategy\Parser;


class TypesSrbStrategy extends BaseParserStrategy
{

    protected  $url = "https://www.astaworld.ru/tyres?availability=0&group%5B%5D=90&set%5B%5D=0&b%5B%5D=185&h%5B%5D=65&d%5B%5D=R15&brand%5B%5D=MICHELIN";

    public function run()
    {
        // TODO: Implement run() method.
        $crawler = $this->getCrawler();
    }
}
