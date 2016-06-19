<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Transaction;
use AppBundle\Form\StockQuantityBought;
use AppBundle\Form\StockQuantityBoughtFormType;
use AppBundle\Form\StockQuantitySold;
use AppBundle\Form\StockQuantitySoldFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

        $data = $this->get('db_data_getter')->getDataArrayWithSymbolAsKey($stockSymbols, ['price', 'name', 'symbol']);

        return ['data' => $data, 'portfolioId' => $portfolioId];
    }

    /**
     * @Route("portfolio/{portfolioId}/transaction/sell/{symbol}", name="sellSpecificStock", requirements={"portfolioId": "\d+"})
     * @Template("@App/stock/sellSpecificStock.html.twig")
     */
    public function sellSpecificAction(Request $request,$portfolioId, $symbol)
    {
        $data = $this->get('db_data_getter')->getDataArrayWithSymbolAsKey([$symbol], ['price', 'symbol', 'name']);

        $portfolio = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Portfolio')
            ->find($portfolioId);

        $holding = $this->getDoctrine()
            ->getRepository('AppBundle:Holding')
            ->findOneBy(array(
                'portfolio' => $portfolio,
                'stockSymbol' => $symbol
            ));

        $stockQuantitySold = new StockQuantitySold();
        $form = $this->createForm(StockQuantitySoldFormType::class, $stockQuantitySold)
            ->add('Sell', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                $transaction = $this->get('trader')->sellStock(
                    $portfolio,
                    $holding,
                    $data[$symbol],
                    $form->getData()->getQuantity()
                );

                $this->addFlash(
                    'success',
                    "You sold " . $form->getData()->getQuantity() . " shares of " . $data[$symbol]['name'] . " for " . $transaction['stockCost'] / 100 . "$ & commission of: " . $transaction['commission'] / 100 . "$");
                return $this->redirectToRoute("showPortfolio", ['id' => $portfolio->getId()]);
        }

        return ['data' => $data[$symbol], 'portfolio' => $portfolio, 'form' => $form->createView(), 'holding' => $holding];
    }

    /**
     * @Route("portfolio/{portfolioId}/transaction/buy/{symbol}", name="buySpecificStock", requirements={"portfolioId": "\d+"})
     * @Template("@App/stock/buySpecificStock.html.twig")
     */
    public function buySpecificAction(Request $request,$portfolioId, $symbol)
    {
        $data = $this->get('db_data_getter')->getDataArrayWithSymbolAsKey([$symbol], ['price', 'symbol', 'name']);
        
        $portfolio = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Portfolio')
            ->find($portfolioId);

        $maxAmount = $this->get('trader')
            ->countMaximumQuantityOfStock(
                $portfolio->getPresentCashAmount(),
                $data[$symbol]['price'],
                $portfolio->getDifficulty());

        $stockQuantityBought = new StockQuantityBought();
        $form = $this->createForm(StockQuantityBoughtFormType::class, $stockQuantityBought)
            ->add('Buy', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $transaction = $this->get('trader')->buyStock(
                    $portfolio, 
                    $data[$symbol], 
                    $form->getData()->getQuantity()
                );

                $this->addFlash(
                    'success', 
                    "You bought " . $form->getData()->getQuantity() . " shares of " . $data[$symbol]['name'] . " for " . $transaction['stockCost'] / 100 . "$ & commission of: " . $transaction['commission'] / 100 . "$");
                return $this->redirectToRoute("showPortfolio", ['id' => $portfolio->getId()]);
            }
            catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirectToRoute("showPortfolio", ['id' => $portfolio->getId()]);
            }
        }

        return ['data' => $data[$symbol], 'portfolio' => $portfolio, 'maxAmount' => $maxAmount, 'form' => $form->createView()];
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

    /**
     * @Route("/checkSharedTransactions", name="checkSharedTransactions", options={"expose"=true})
     */
    public function checkSharedTransactionsAction()
    {
        $sharedTransactions = $this->getDoctrine()
            ->getRepository("AppBundle:Transaction")
            ->getSharedTransactions();

        if (!$sharedTransactions) {
            return new JsonResponse(null);
        }
        
        return new JsonResponse($sharedTransactions);
    }
}