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
            $transaction->initialise();
            if (!$this->isGranted('CREATE', $transaction->getTransaction())) {
                return $this->json(['error' => 'Transaction limits exceeded'], 403);
            }
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
    public function submitNewTransactionAction(Request $request, $id, EntityManagerInterface $em) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['code']) && $data['code'] === 111) {
            $transaction = $this->getDoctrine()
                ->getRepository(Transaction::class)
                ->find($id);

            if (!$transaction) {
                return $this->json(['error' => 'Transaction not found'], 404);
            }

            $transaction->setStatus('SIGNED-PENDING');
            $em->persist($transaction);
            $em->flush();
        }

        return $this->json(['error' => 'Invalid code'], 401);
    }
}
