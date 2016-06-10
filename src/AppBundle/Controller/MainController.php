<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Stock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("@App/index.html.twig")
     */
    public function indexAction()
    {
        $stockSymbols = $this->getDoctrine()
            ->getRepository("AppBundle:Stock")
            ->getAllSymbols();

        $sharedTransactions = $this->getDoctrine()
            ->getRepository("AppBundle:Transaction")
            ->getSharedTransactions();

        $users = [];
        $portfolioNumber = 0;
        foreach ($sharedTransactions as $transaction) {
            $users[$transaction->getId()]= $transaction->getPortfolio()->getUser()->getUsername();
            $portfolioNumber++;
        }

        $data = $this->get('data_getter')->getData($stockSymbols, ['price', 'name', 'symbol', 'utctime']);
        
        return ['data' => $data, 'sharedTransactions' => $sharedTransactions, 'users' => $users];
    }
}