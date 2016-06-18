<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="historicalData")
 */
class HistoricalData
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Stock", inversedBy="historicalData")
     */
    private $stock;

    /**
     * @ORM\Column(type="datetime")
     */
    private $downloadDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dataTime;

    /**
     * @ORM\Column(type="float", scale=6, precision=30)
     */
    private $price;

    /**
     * @ORM\Column(type="float", scale=6, precision=30)
     */
    private $priceChange;

    /**
     * @ORM\Column(type="float", scale=6, precision=30)
     */
    private $changePercent;

    /**
     * @ORM\Column(type="float", scale=6, precision=30)
     */
    private $dayHigh;

    /**
     * @ORM\Column(type="float", scale=6, precision=30)
     */
    private $dayLow;

    /**
     * @ORM\Column(type="float", scale=6, precision=30)
     */
    private $yearHigh;

    /**
     * @ORM\Column(type="float", scale=6, precision=30)
     */
    private $yearLow;

    public function getId()
    {
        return $this->id;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getDownloadDate()
    {
        return $this->downloadDate;
    }

    public function setDownloadDate($downloadDate)
    {
        $this->downloadDate = $downloadDate;
    }

    public function getDataTime()
    {
        return $this->dataTime;
    }

    public function setDataTime($dataTime)
    {
        $this->dataTime = $dataTime;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getPriceChange()
    {
        return $this->priceChange;
    }

    public function setPriceChange($priceChange)
    {
        $this->priceChange = $priceChange;
    }

    public function getChangePercent()
    {
        return $this->changePercent;
    }

    public function setChangePercent($changePercent)
    {
        $this->changePercent = $changePercent;
    }

    public function getDayHigh()
    {
        return $this->dayHigh;
    }

    public function setDayHigh($dayHigh)
    {
        $this->dayHigh = $dayHigh;
    }

    public function getDayLow()
    {
        return $this->dayLow;
    }

    public function setDayLow($dayLow)
    {
        $this->dayLow = $dayLow;
    }

    public function getYearHigh()
    {
        return $this->yearHigh;
    }

    public function setYearHigh($yearHigh)
    {
        $this->yearHigh = $yearHigh;
    }

    public function getYearLow()
    {
        return $this->yearLow;
    }

    public function setYearLow($yearLow)
    {
        $this->yearLow = $yearLow;
    }
}