<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class StockRepository extends EntityRepository
{
    public function getAllSymbols()
    {
        $stocks = $this->createQueryBuilder('stock')
            ->select('stock.symbol')
            ->getQuery()
            ->getResult();

        $stockSymbols = [];

        foreach ($stocks as $symbol)
        {
            $stockSymbols[] = $symbol['symbol'];
        }
        return $stockSymbols;
    }
}