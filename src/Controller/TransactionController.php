<?php

namespace App\Controller;

use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TransactionController.php',
        ]);
    }

    /**
     * @Route("/get-all-transactions", name="get-all-transactions", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllTransactionsAction()
    {
        return $this->json($this->getDoctrine()->getRepository(Transaction::class)->getAllAsArray());
    }

    /**
     * @Route("/create-new-transaction", name="create-new-transaction", methods={"POST"})
     */
    public function createNewTransactionAction()
    {

    }

    /**
     * @Route("/sign-transation/{id}", name="sign-transaction", methods={"PUT"})
     */
    public function submitNewTransactionAction()
    {

    }
}
