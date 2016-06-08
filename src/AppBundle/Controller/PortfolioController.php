<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Portfolio;
use AppBundle\Type\PortfolioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\CssSelector\Tests\Parser\ReaderTest;
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

    }

    /**
     * @Route("/{id}", name="showPortfolio", requirements={"id": "\d+"})
     * @Template("@App/portfolio/showPortfolio.html.twig")
     */
    public function showAction()
    {

    }

    /**
     * @Route("/new", name="newPortfolio")
     * @Template("@App/portfolio/newPortfolio.html.twig")
     */
    public function newAction(Request $request)
    {
        $portfolio = new Portfolio();
        $form = $this->createForm(PortfolioType::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();
            $portfolio->setUser($user);
            $em->persist($portfolio);
            $em->flush();

            return $this->redirectToRoute("showPortfolio", ['id' => $portfolio->getId()]);
        }

        return ['form' => $form->createView()];
    }
}