<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class PortfolioRepository extends EntityRepository
{
    public function numberOfRows()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}