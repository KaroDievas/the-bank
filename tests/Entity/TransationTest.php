<?php

namespace App\Tests\Entity;

use App\Entity\Transaction;
use PHPUnit\Framework\TestCase;

class TransationTest extends TestCase
{
    public function testTransaction()
    {
        $transaction = new Transaction();
        $transaction->setStatus('CREATED');
        $transaction->setDetails("Details");
        $transaction->setFeeAmount(2);
        $transaction->setCreatedAt(new \DateTime('now'));
        $transaction->setAmount(10);
        $transaction->setCurrency('eur');
        $transaction->setReceiverName('Name');
        $transaction->setUserId(1);
        $transaction->setReceiverAccount('LT757044299245836165');

        $this->assertEquals('CREATED', $transaction->getStatus());
        $this->assertEquals('Details', $transaction->getDetails());
        $this->assertEquals(2, $transaction->getFeeAmount());
        $this->assertEquals(10, $transaction->getAmount());
        $this->assertEquals('eur', $transaction->getCurrency());
        $this->assertEquals('Name', $transaction->getReceiverName());
        $this->assertEquals(1, $transaction->getUserId());
        $this->assertEquals('LT757044299245836165', $transaction->getReceiverAccount());
        $this->assertInstanceOf(\DateTime::class, $transaction->getCreatedAt());
        $this->assertTrue(is_array($transaction->toArray()));
    }

}