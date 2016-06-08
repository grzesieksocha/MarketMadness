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
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     */
    private $difficulty;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $initialCashAmount;

    /**
     * @ORM\Column(type="integer")
     */
    private $presentCashAmount;
    
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

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;
    
    public function __construct()
    {
        $this->holdings = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }
    
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setInitialCashAmount($initialCashAmount)
    {
        $this->initialCashAmount = $initialCashAmount;

        return $this;
    }
    
    public function getInitialCashAmount()
    {
        return $this->initialCashAmount;
    }

    public function getPresentCashAmount()
    {
        return $this->presentCashAmount;
    }

    public function setPresentCashAmount($presentCashAmount)
    {
        $this->presentCashAmount = $presentCashAmount;
    }
    
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
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

    public function countPortfolioReturn()
    {
        $return = ($this->presentCashAmount - $this->initialCashAmount) / $this->initialCashAmount * 100;

        return $return;
    }
}
