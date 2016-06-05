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

        $yahooApi = $this->get('yahoo_api');
        $data = $yahooApi->getPrices($stockSymbols);

//        $data = json_decode($data, true);
//        foreach ($data['list']['resources'] as )

        return ['data' => $data];
    }
}