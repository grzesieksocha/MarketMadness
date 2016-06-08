<?php


namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/stock")
 */
class StockController extends Controller
{
    /**
     * @Route("/{symbol}", name="stockDetails")
     * @Template("@App/stock/stockDetails.html.twig")
     */
    public function showDetailsAction($symbol)
    {
        $symbols[] = $symbol;
        $stockData = $this->get('data_getter')->getFullData($symbols)[0];

        return ['stock' => $stockData];
    }
}