<?php


namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="holding")
 */
class Holding
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Portfolio", inversedBy="holdings")
     */
    private $portfolio;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="The stock symbol cannot be blank")
     * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}")
     */
    private $stockSymbol;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $stockQuantity;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     */
    private $averageBuyPrice;
    
    public function __construct()
    {
        $this->stockQuantity = 0;
        $this->averageBuyPrice = 0;
    }
    
    public function addHolding($quantity, $price)
    {
        $this->averageBuyPrice = 
            (($this->stockQuantity * $this->averageBuyPrice) + ($quantity * $price)) / ($this->stockQuantity + $quantity);
        $this->stockQuantity += $quantity;
            
    }
    
    public function getId()
    {
        return $this->id;
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
    
    public function setAverageBuyPrice($averageBuyPrice)
    {
        $this->averageBuyPrice = $averageBuyPrice;

        return $this;
    }
    
    public function getAverageBuyPrice()
    {
        return $this->averageBuyPrice;
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
}
