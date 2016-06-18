<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Intl\Exception\MissingResourceException;

class HistoricalDataRepository extends EntityRepository
{
    public function getLatestStockData($stock)
    {
        $data = $this->createQueryBuilder('data')
            ->where('data.stock = :stock')
            ->setParameter('stock', $stock)
            ->orderBy('data.downloadDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if ($data) {
            return $data[0];
        } else {
            throw new MissingResourceException("Please wait for stock data to be uploaded");
        }
    }
}