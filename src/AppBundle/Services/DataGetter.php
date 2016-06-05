<?php

namespace AppBundle\Services;

class DataGetter
{
    private $yahooApi;

    public function __construct(YahooApi $yahooApi)
    {
        $this->yahooApi = $yahooApi;
    }

    public function getData(array $stocks, array $fields)
    {
        $data = $this->yahooApi->getDetails($stocks);
        $data = json_decode($data, true);
        $result = [];
        $stockNumber = 0;
        foreach ($data['list']['resources'] as $stock)
        {
            foreach ($fields as $field)
            {
                $result[$stockNumber][$field] = $stock['resource']['fields'][$field];
            }
            $stockNumber++;
        }
        return $result;
    }
}