<?php

namespace AppBundle\Services;

use AppBundle\Entity\Holding;
use AppBundle\Entity\Portfolio;
use AppBundle\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class Trader
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buyStock(Portfolio $portfolio, array $stockData, $quantity)
    {
        // Count cash spent
        $stockCost = floor($stockData['price'] * $quantity * 100);
        $commission = floor($stockCost * $this->getCommission($portfolio->getDifficulty()));
        $totalCost = $stockCost + $commission;

        // Add holding
        $holding = new Holding();
        $holding->setPortfolio($portfolio);
        $holding->setAverageBuyPrice($stockData['price']);
        $holding->setStockQuantity($quantity);
        $holding->setStockSymbol($stockData['symbol']);
        $this->em->persist($holding);
        
        // Add transaction
        $transaction = new Transaction();
        $transaction->setStockQuantity($quantity);
        $transaction->setPortfolio($portfolio);
        $transaction->setStockSymbol($stockData['symbol']);
        $transaction->setStockPrice($stockData['price']);
        $transaction->setTransactionType('buy');
        $this->em->persist($transaction);
        
        // Modify portfolio cash
        $portfolioCashAmount = $portfolio->getPresentCashAmount();
        $portfolioCashAmount = $portfolioCashAmount - $totalCost;
        $portfolio->setPresentCashAmount($portfolioCashAmount);
        $this->em->persist($transaction);

        $this->em->flush();
        
        return ['cost' => $stockCost, 'commission' => $commission];
    }

    public function countMaximumQuantityOfStock($cashAmount, $stockPrice, $difficulty)
    {
        $commission = $this->getCommission($difficulty);
        $stockPrice = $stockPrice + $stockPrice * $commission;
        $maximumQuantityOfStock = floor(($cashAmount/100)/$stockPrice);
        
        return $maximumQuantityOfStock;
    }

    private function getCommission($difficulty)
    {
        switch ($difficulty) {
            case 'easy':
                return 0;
            case 'medium':
                return 0.02;
            case 'hard':
                return 0.05;
        }
    }
}