<?php


namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TransactionRepository")
 * @ORM\Table(name="transaction")
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Portfolio", inversedBy="transactions")
     */
    private $portfolio;

    /**
     * @ORM\Column(type="string")
     */
    private $stockSymbol;

    /**
     * @ORM\Column(type="integer")
     */
    private $stockQuantity;

    /**
     * @ORM\Column(type="float")
     */
    private $stockPrice;

    /**
     * @ORM\Column(type="string")
     */
    private $transactionType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isShared;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    
    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }
    
    public function setStockSymbol($stockSymbol)
    {
        $this->stockSymbol = $stockSymbol;

        return $this;
    }
    
    public function getStockSymbol()
    {
        return $this->stockSymbol;
    }
    
    public function setStockQuantity($stockQuantity)
    {
        $this->stockQuantity = $stockQuantity;

        return $this;
    }
    
    public function getStockQuantity()
    {
        return $this->stockQuantity;
    }
    
    public function setStockPrice($stockPrice)
    {
        $this->stockPrice = $stockPrice;

        return $this;
    }

    public function getStockPrice()
    {
        return $this->stockPrice;
    }

    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    public function getTransactionType()
    {
        return $this->transactionType;
    }

    public function setPortfolio(Portfolio $portfolio = null)
    {
        $this->portfolio = $portfolio;

        return $this;
    }
    
    public function getPortfolio()
    {
        return $this->portfolio;
    }

    public function getIsShared()
    {
        return $this->isShared;
    }

    public function setIsShared($isShared)
    {
        $this->isShared = $isShared;
    }
}
