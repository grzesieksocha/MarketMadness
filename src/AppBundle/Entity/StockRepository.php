<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class StockRepository extends EntityRepository
{
    /**
     * Extract a symbol of every Stock.
     *
     * @return array containing stock symbols
     * @throws EntityNotFoundException
     */
    public function getAllSymbols()
    {
        $stocks = $this->createQueryBuilder('stock')
            ->select('stock.symbol')
            ->getQuery()
            ->getResult();

        if (!$stocks) {
            throw new EntityNotFoundException("No stocks in the database. Please use doctrine:fixtures:load");
        }

        $stockSymbols = [];
        foreach ($stocks as $stock)
        {
            $stockSymbols[] = $stock['symbol'];
        }
        return $stockSymbols;
    }

    public function numberOfRows()
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}