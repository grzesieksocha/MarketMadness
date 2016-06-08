<?php


namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("portfolio/{portfolioId}/transaction", requirements={"portfolioId": "\d+"})
 */
class TransactionController extends Controller
{
    /**
     * @Route("/buy", name="buyStock")
     * @Template("@App/transactions/buyStock.html.twig")
     */
    public function buyAction($portfolioId)
    {
        $stockSymbols = $this->getDoctrine()
            ->getRepository("AppBundle:Stock")
            ->getAllSymbols();

        $data = $this->get('data_getter')->getData($stockSymbols, ['price', 'name', 'symbol']);

        return ['data' => $data, 'portfolioId' => $portfolioId];
    }

    /**
     * @Route("/buy/{symbol}", name="buySpecificStock")
     * @Template("@App/stock/buySpecificStock.html.twig")
     */
    public function buySpecificAction($portfolioId, $symbol)
    {
        $symbolArray[] = $symbol;
        $data = $this->get('data_getter')->getData($symbolArray, ['price', 'symbol']);

        return ['data' => $data];
    }
    
}