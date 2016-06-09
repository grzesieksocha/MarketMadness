<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Portfolio;
use AppBundle\Form\PortfolioFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        $holdings = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Holding')
            ->findBy(['portfolio' => $portfolio]);
        
        return [
            'portfolio' => $portfolio, 
            'holdings' => $holdings,
            'return' => $portfolio->countPortfolioReturn()
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