<?php

namespace AppBundle\Services;

class DataGetter
{
    private $yahooApi;

    public function __construct(YahooApi $yahooApi)
    {
        $this->yahooApi = $yahooApi;
    }
    
    public function getDataArrayWithSymbolAsKey(array $stocks, array $fields = null)
    {
        $data = $this->decoder($stocks);
        $result = [];
        if (!$fields) {
            foreach ($data['list']['resources'] as $stock) {
                $result[$stock['resource']['fields']['symbol']] = $stock['resource']['fields'];
            }
        } else {
            foreach ($data['list']['resources'] as $stock) {
                foreach ($fields as $field) {
                    $result[$stock['resource']['fields']['symbol']][$field] = $stock['resource']['fields'][$field];
                }
            }
        }
        return $result;
    }
    
    private function decoder(array $stocks)
    {
        return json_decode($this->yahooApi->getDetails($stocks), true);
    }
}