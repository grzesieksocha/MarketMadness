<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/portfolio")
 */
class PortfolioController extends Controller
{
    /**
     * @Route("/new", name="newPortfolio")
     * @Template("@App/portfolio/newPortfolio.html.twig")
     */
    public function newAction()
    {

    }
}