<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Portfolio", mappedBy="user")
     */
    private $portfolios;
    
    public function __construct()
    {
        parent::__construct();
        $this->portfolios = new ArrayCollection();
    }

    public function addPortfolio(Portfolio $portfolio)
    {
        $portfolio->setPortfolio($this);
        $this->portfolios[] = $portfolio;
    }

    public function removePortfolio(Portfolio $portfolio)
    {
        $this->portfolios->removeElement($portfolio);
    }

    public function getPortfolios()
    {
        return $this->portfolios;
    }
}
