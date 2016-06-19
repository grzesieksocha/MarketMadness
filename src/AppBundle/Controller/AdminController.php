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

    /**
     * @Route("/showUsers", name="showAllUsers")
     * @Template("@App/admin/showAllUsers.html.twig")
     */
    public function showAllUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        dump($users);
        return ['users' => $users];
    }

    /**
     * @Route("/showPortfolios", name="showAllPortfolios")
     * @Template("@App/admin/showAllPortfolios.html.twig")
     */
    public function showAllPortfoliosAction()
    {
        $portfolios = $this->getDoctrine()->getRepository('AppBundle:Portfolio')->findAll();
        return ['portfolios' => $portfolios];
    }

    /**
     * @Route("/showTransactions", name="showAllTransactions")
     * @Template("@App/admin/showAllTransactions.html.twig")
     */
    public function showAllTransactionsAction()
    {
        $transactions = $this->getDoctrine()->getRepository('AppBundle:Transaction')->findAll();
        return ['transactions' => $transactions];
    }

    /**
     * @Route("/showComments", name="showAllComments")
     * @Template("@App/admin/showAllComments.html.twig")
     */
    public function showAllCommentsAction()
    {
    }
}