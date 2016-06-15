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

    /**
     * Extract the username of the transaction provided
     *
     * @param $transactionId
     * @return null|object
     */
    public function getTransactionUser($transactionId)
    {
        $portfolio = $this->find($transactionId);
        
        return $portfolio;
    }
}