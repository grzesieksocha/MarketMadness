<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
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
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $symbol;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\HistoricalData", mappedBy="stock")
     */
    private $historicalData;

    public function __construct()
    {
        $this->historicalData = new ArrayCollection();
    }

    public function addHistoricalData(HistoricalData $historicalData)
    {
        $this->historicalData[] = $historicalData;
    }

    public function removeHistoricalData(HistoricalData $historicalData)
    {
        $this->historicalData->removeElement($historicalData);
    }

    public function getHistoricalData()
    {
        return $this->historicalData;
    }

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
