<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use AppBundle\Form\StockQuantityBought;
use AppBundle\Form\StockQuantityBoughtFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * @Route("portfolio/{portfolioId}/transaction/buy", name="buyStock", requirements={"portfolioId": "\d+"})
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
     * @Route("portfolio/{portfolioId}/transaction/buy/{symbol}", name="buySpecificStock", requirements={"portfolioId": "\d+"})
     * @Template("@App/stock/buySpecificStock.html.twig")
     */
    public function buySpecificAction(Request $request,$portfolioId, $symbol)
    {
        $symbolArray[] = $symbol;
        $data = $this->get('data_getter')->getData($symbolArray, ['price', 'symbol', 'name'])[0];
        
        $portfolio = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Portfolio')
            ->find($portfolioId);

        $maxAmount = $this->get('trader')
            ->countMaximumQuantityOfStock(
                $portfolio->getPresentCashAmount(),
                $data['price'],
                $portfolio->getDifficulty());

        $stockQuantityBought = new StockQuantityBought();
        $form = $this->createForm(StockQuantityBoughtFormType::class, $stockQuantityBought)
            ->add('Buy', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $transaction = $this->get('trader')->buyStock(
                    $portfolio, 
                    $data, 
                    $form->getData()->getQuantity()
                );

                $this->addFlash(
                    'success', 
                    "You bought " . $form->getData()->getQuantity() . " shares of " . $data['name'] . " for " . $transaction['stockCost'] / 100 . "$ & commission of: " . $transaction['commission'] / 100 . "$");
                return $this->redirectToRoute("showPortfolio", ['id' => $portfolio->getId()]);
            }
            catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute("showPortfolio", ['id' => $portfolio->getId()]);
            }
        }

        return ['data' => $data, 'portfolio' => $portfolio, 'maxAmount' => $maxAmount, 'form' => $form->createView()];
    }

    /**
     * @Route("/share/{transactionId}", name="shareTransaction", options={"expose"=true})
     */
    public function shareTransaction(Request $request, $transactionId)
    {
        $em = $this->getDoctrine()->getManager();
        $transaction = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Transaction')
            ->find($transactionId);

        $action = $request->request->get('action');

        if ($action == 'share') {
            $transaction->setIsShared(true);
        } elseif ($action == 'unshare') {
            $transaction->setIsShared(false);
        }

        $em->flush();

        return new JsonResponse($action);
    }
}