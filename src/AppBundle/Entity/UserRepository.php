<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function numberOfRows()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}