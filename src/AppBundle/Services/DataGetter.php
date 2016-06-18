<?php

namespace AppBundle\Services;

class DataGetter
{
    private $yahooApi;

    public function __construct(YahooApi $yahooApi)
    {
        $this->yahooApi = $yahooApi;
    }
    
    public function getDataWithSymbolAsKey(array $stocks)
    {
        $data = $this->decoder($stocks);
        $result = [];
        $stockNumber = 0;
        foreach ($data['list']['resources'] as $stock)
        {
            $result[$stock['resource']['fields']['symbol']] = $stock['resource']['fields'];
            $stockNumber++;
        }
        return $result;
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

    public function getFullData(array $stocks)
    {
        $data = $this->decoder($stocks);
        $result = [];
        $stockNumber = 0;
        foreach ($data['list']['resources'] as $stock)
        {
            $result[$stockNumber] = $stock['resource']['fields'];
            $stockNumber++;
        }
        return $result;
    }
    
    private function decoder(array $stocks)
    {
        return json_decode($this->yahooApi->getDetails($stocks), true);
    }
}