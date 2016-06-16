<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Stock;
use AppBundle\Form\StockFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/home", name="mainAdminPage")
     * @Template("@App/admin/mainPage.html.twig")
     */
    public function indexAction()
    {}

    /**
     * @Route("/addStock", name="addYahooStock")
     * @Template("@App/admin/addStock.twig")
     */
    public function addYahooAction(Request $request)
    {
        $stock = new Stock();
        $form = $this->createForm(StockFormType::class, $stock)
            ->add('Add', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($stock);
            $em->flush();

            $this->addFlash('success', 'New stock added');

            return $this->redirectToRoute("mainAdminPage");
        }

        return ['form' => $form->createView()];
    }
}