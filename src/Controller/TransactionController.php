<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Transaction;
use App\Providers\TransactionProviderFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionController extends AbstractController
{

    /**
     * @Route("/get-all-transactions", name="get-all-transactions", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAllTransactionsAction() : JsonResponse
    {
        return $this->json($this->getDoctrine()->getRepository(Transaction::class)->getAllAsArray(), 200);
    }

    /**
     * @Route("/create-new-transaction", name="create-new-transaction", methods={"POST"})
     */
    public function createNewTransactionAction(Request $request, TransactionProviderFactory $transactionProviderFactory, SerializerInterface $serializer) : JsonResponse
    {
        try {
            $transaction = $transactionProviderFactory->getProvider();
            $transaction->submit();
            return $this->json($serializer->serialize($transaction->getTransaction(), 'json'), 201);
        }
        catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * @Route("/sign-transation/{id}", name="sign-transaction", methods={"PUT"})
     */
    public function submitNewTransactionAction(Request $request, $id) : JsonResponse
    {

    }
}
