<?php

namespace App\Tests\Security\Voter;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Security\Voter\TransactionVoter;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class TransactionVoterTest extends TestCase
{

    public function testReachedHourLimit()
    {
        $transactionRepo = $this->createMock(TransactionRepository::class);
        $transactionRepo->method('getTotalTransactionsCountPerHourByUserId')->willReturn(10);

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($transactionRepo);

        $transaction = $this->createMock(Transaction::class);
        $transaction->method('getUserId')->willReturn(99999);

        $transactionVoter = new TransactionVoter($em);

        $token = new AnonymousToken('secret', 'anonymous');
        $this->assertEquals(0, $transactionVoter->vote($token, $transaction, ['CREATE2']));
        $this->assertEquals(-1, $transactionVoter->vote($token, $transaction, ['CREATE']));
    }

    public function testReachedTotalLimit()
    {
        $transactionRepo = $this->createMock(TransactionRepository::class);
        $transactionRepo->method('getTotalTransactionsCountPerHourByUserId')->willReturn(10);
        $transactionRepo->method('getTotalAmountByUserAndByCurrency')->willReturn(1000);

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())
            ->method('getRepository')
            ->willReturn($transactionRepo);

        $transaction = $this->createMock(Transaction::class);
        $transaction->method('getUserId')->willReturn(99999);
        $transaction->method('getCurrency')->willReturn('eur');
        $transaction->method('getAmount')->willReturn(1);
        $transaction->method('getFeeAmount')->willReturn(1);

        $transactionVoter = new TransactionVoter($em);

        $token = new AnonymousToken('secret', 'anonymous');
        $this->assertEquals(-1, $transactionVoter->vote($token, $transaction, ['CREATE']));
    }

}