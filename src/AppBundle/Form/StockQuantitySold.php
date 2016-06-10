<?php


namespace AppBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;

class StockQuantitySold
{
    /**
     * @Assert\NotBlank()
     * @Assert\Range(min="0", minMessage="Please specify a positive number of stocks")
     */
    private $quantity;

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }
}