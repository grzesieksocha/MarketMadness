<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{
    public function getSharedTransactions()
    {
        $sharedTransactions = $this->createQueryBuilder('transaction')
            ->where('transaction.isShared = true')
            ->orderBy('transaction.date', 'DESC')
            ->getQuery()
            ->getResult();

        return $sharedTransactions;
    }

    public function getTransactionUser($transactionId)
    {
        $portfolio = $this->find($transactionId);
        
        return $portfolio;
    }
}