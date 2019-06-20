<?php

namespace App\Tests\Repository;

use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TransactionRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;


    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

    }

    public function testGetTotalTransactionsCountPerHourByUserId()
    {
        $this->assertEquals(0, $this->entityManager->getRepository(Transaction::class)->getTotalAmountByUserId(99999));
    }

    public function testGetTotalAmountByUserAndByCurrency()
    {
        $this->assertEquals(0, $this->entityManager->getRepository(Transaction::class)->getTotalAmountByUserAndByCurrency(99999, 'eur'));
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
        $this->serializer = null;
    }
}