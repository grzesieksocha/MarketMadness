<?php


namespace AppBundle\Services;

use GuzzleHttp\Client;

class YahooApi
{
    const API_URL = "http://finance.yahoo.com/webservice/v1/symbols/";

    private $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getDetails(array $stocks)
    {
        $query_url = self::API_URL . implode(',', $stocks) . "/quote?format=json&view=detail";
        $data = $this->client->request('GET', $query_url)->getBody()->getContents();
        return $data;
    }
}