<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="portfolio")
 */
class Portfolio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Cash hold in the portfolio in cents.
     *
     * @ORM\Column(type="integer")
     */
    private $cashAmount;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Holding", mappedBy="portfolio")
     */
    private $holdings;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Transaction", mappedBy="portfolio")
     */
    private $transactions;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="portfolios")
     */
    private $user;

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function __construct()
    {
        $this->holdings = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setCashAmount($cashAmount)
    {
        $this->cashAmount = $cashAmount;

        return $this;
    }
    
    public function getCashAmount()
    {
        return $this->cashAmount;
    }
    
    public function addHolding(Holding $holding)
    {
        $this->holdings[] = $holding;

        return $this;
    }
    
    public function removeHolding(Holding $holding)
    {
        $this->holdings->removeElement($holding);
    }
    
    public function getHoldings()
    {
        return $this->holdings;
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;

        return $this;
    }
    
    public function removeTransaction(Transaction $transaction)
    {
        $this->transactions->removeElement($transaction);
    }
    
    public function getTransactions()
    {
        return $this->transactions;
    }
}
