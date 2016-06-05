<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\StockRepository")
 * @ORM\Table(name="stock")
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $symbol;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    public function getId()
    {
        return $this->id;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }


}