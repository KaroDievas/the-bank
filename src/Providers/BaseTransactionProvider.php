<?php
declare(strict_types=1);

namespace App\Providers;


use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseTransactionProvider
{
    protected $transaction;
    private $serializer;
    private $em;
    private $data;

    const BASE_FEE_RATE = 0.10;
    const REDUCED_FEE_RATE = 0.05;
    const REDUCED_RATE_FROM_AMOUNT = 100;

    public function __construct($data, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->data = $data;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * Applies custom rules for transaction details
     */
    public abstract function setDetails(): void;


    /**
     * @return Transaction
     */
    public function getTransaction(): Object
    {
        return $this->transaction;
    }

    public function initialise(): void
    {
        $this->transaction = $this->serializer->deserialize($this->data, Transaction::class, 'json');

        $this->setDetails();
        $this->applyFee();
        $this->transaction->setStatus('CREATED');
        $this->transaction->setCreatedAt(new \DateTime('now'));
    }

    /**
     * Submits new transaction to database
     *
     * @throws \Exception
     */
    public function submit(): void
    {
        $this->em->persist($this->transaction);
        $this->em->flush();
    }

    /**
     * Function applies fee to current transaction
     */
    private function applyFee(): void
    {
        $totalAmount = $this->em->getRepository(Transaction::class)->getTotalAmountByUserId($this->transaction->getUserId());
        $currentAmount = $this->transaction->getAmount();
        $fee = $currentAmount * self::BASE_FEE_RATE;
        if ($totalAmount >= self::REDUCED_RATE_FROM_AMOUNT) {
            $fee = $currentAmount * self::REDUCED_FEE_RATE;
        }
        $this->transaction->setFeeAmount($fee);
    }
}