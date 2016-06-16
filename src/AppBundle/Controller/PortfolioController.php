<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Portfolio;
use AppBundle\Form\PortfolioFormType;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/portfolio")
 */
class PortfolioController extends Controller
{
    /**
     * @Route("/list", name="listPortfolios")
     * @Template("@App/portfolio/listPortfolios.html.twig")
     */
    public function listAction()
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'You must log in!');
        $portfolios = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Portfolio')
            ->findBy(['user' => $this->getUser()]);
        
        return ['portfolios' => $portfolios];
    }

    /**
     * @Route("/{id}", name="showPortfolio", requirements={"id": "\d+"})
     * @Template("@App/portfolio/showPortfolio.html.twig")
     */
    public function showAction($id)
    {
        $portfolio = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Portfolio')
            ->find($id);

        if (!$portfolio) {
            throw new EntityNotFoundException("Portfolio not found");
        } elseif ($portfolio->getUser() != $this->getUser()) {
            throw new AccessDeniedException("You are not entitled to show this portfolio");
        }

        $holdings = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Holding')
            ->findBy(['portfolio' => $portfolio]);

        $symbols = [];
        foreach ($holdings as $holding) {
            $symbols[] = $holding->getStockSymbol();
        }

        $transactions = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Transaction')
            ->findBy(['portfolio' => $portfolio], ['date' => 'DESC']);

        if ($transactions) {
            $data = $this->get('data_getter')->getData($symbols, ['symbol', 'price']);
            $return = $this->get('trader')->countReturn($portfolio, $holdings, $data);

            $presentPrices = [];
            foreach ($data as $price) {
                $presentPrices[$price['symbol']] = $price['price'];
            }
        } else {
            $presentPrices = null;
            $return = 0;
        }

        return [
            'prices' => $presentPrices,
            'portfolio' => $portfolio, 
            'holdings' => $holdings,
            'transactions' => $transactions,
            'return' => $return
        ];
    }

    /**
     * @Route("/new", name="newPortfolio")
     * @Template("@App/portfolio/newPortfolio.html.twig")
     */
    public function newAction(Request $request)
    {
        $portfolio = new Portfolio();
        $form = $this->createForm(PortfolioFormType::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $portfolio->setUser($user);
            $portfolio->setPresentCashAmount($portfolio->getInitialCashAmount());
            $portfolio->setIsActive(true);
            $em->persist($portfolio);
            $em->flush();

            $this->addFlash('success', 'New portfolio created - let\'s invest!');

            return $this->redirectToRoute("showPortfolio", ['id' => $portfolio->getId()]);
        }

        return ['form' => $form->createView()];
    }
}