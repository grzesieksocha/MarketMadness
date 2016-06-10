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
        $cost = $this->countCost($stockData['price'], $quantity, $portfolio->getDifficulty());

        $this->addHolding($portfolio, $stockData, $quantity);
        $this->addBuyTransaction($portfolio, $stockData, $quantity);

        // Modify portfolio cash
        $portfolioCashAmount = $portfolio->getPresentCashAmount();
        $portfolioCashAmount = $portfolioCashAmount - ($cost['stockCost'] + $cost['commission']);
        $portfolio->setPresentCashAmount($portfolioCashAmount);

        $this->em->flush();
        
        return $cost;
    }

    public function sellStock(Portfolio $portfolio, Holding $holding, array $stockData, $quantity)
    {
        $cost = $this->countCost($stockData['price'], $quantity, $portfolio->getDifficulty());

        $this->removeHolding($holding, $quantity);
        $this->addSellTransaction($portfolio, $stockData, $quantity);

        // Modify portfolio cash
        $portfolioCashAmount = $portfolio->getPresentCashAmount();
        $portfolioCashAmount = $portfolioCashAmount + $cost['stockCost'] - $cost['commission'];
        $portfolio->setPresentCashAmount($portfolioCashAmount);

        $this->em->flush();

        return $cost;
    }

    public function countMaximumQuantityOfStock($cashAmount, $stockPrice, $difficulty)
    {
        $commission = $this->getCommission($difficulty);
        $stockPrice = $stockPrice + $stockPrice * $commission;
        $maximumQuantityOfStock = floor(($cashAmount/100)/$stockPrice);
        
        return $maximumQuantityOfStock;
    }

    private function countCost($price, $quantity, $difficulty)
    {
        $stockCost = floor($price * $quantity * 100);
        $commission = floor($stockCost * $this->getCommission($difficulty));
        return ['stockCost' => $stockCost, 'commission' => $commission];
    }

    private function addHolding($portfolio, $stockData, $quantity)
    {
        $holding = $this->em->getRepository('AppBundle:Holding')
            ->findOneBy(
                array('portfolio' => $portfolio, 'stockSymbol' => $stockData['symbol'])
            );

        if (!$holding) {
            $holding = new Holding();
            $holding->setPortfolio($portfolio);
            $holding->setAverageBuyPrice($stockData['price']);
            $holding->setStockQuantity($quantity);
            $holding->setStockSymbol($stockData['symbol']);
            $this->em->persist($holding);
        } else {
            $holding->addHolding($quantity, $stockData['price']);
        }
    }

    private function removeHolding(Holding $holding, $quantity)
    {
        if (($holding->getStockQuantity() - $quantity) == 0) {
            $this->em->remove($holding);
        } else {
            $pastHolding = $holding->getStockQuantity();
            $holding->setStockQuantity($pastHolding - $quantity);
        }
    }

    private function addBuyTransaction(Portfolio $portfolio, array $stockData, $quantity)
    {
        $transaction = new Transaction();
        $transaction->setStockQuantity($quantity);
        $transaction->setPortfolio($portfolio);
        $transaction->setStockSymbol($stockData['symbol']);
        $transaction->setStockPrice($stockData['price']);
        $transaction->setTransactionType('buy');
        $transaction->setIsShared(false);
        $this->em->persist($transaction);
    }

    private function addSellTransaction(Portfolio $portfolio, array $stockData, $quantity)
    {
        $transaction = new Transaction();
        $transaction->setStockQuantity($quantity);
        $transaction->setPortfolio($portfolio);
        $transaction->setStockSymbol($stockData['symbol']);
        $transaction->setStockPrice($stockData['price']);
        $transaction->setTransactionType('sell');
        $transaction->setIsShared(false);
        $this->em->persist($transaction);
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