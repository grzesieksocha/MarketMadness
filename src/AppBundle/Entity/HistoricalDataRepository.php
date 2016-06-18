<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class HistoricalDataRepository extends EntityRepository
{
    public function getLatestStockData($stock)
    {
        return $this->createQueryBuilder('data')
            ->where('data.stock = :stock')
            ->setParameter('stock', $stock)
            ->orderBy('data.downloadDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0];
    }
}