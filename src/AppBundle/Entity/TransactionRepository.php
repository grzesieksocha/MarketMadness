<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{
    /**
     * @return array containing shared transactions
     */
    public function getSharedTransactions()
    {
        $sharedTransactions = $this->createQueryBuilder('transaction')
            ->where('transaction.isShared = true')
            ->orderBy('transaction.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $sharedTransactions;
    }

    public function numberOfRows()
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function numberOfShared()
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isShared = true')
            ->select('COUNT(t)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}