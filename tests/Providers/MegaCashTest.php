<?php

namespace App\Tests\Providers;

use App\Providers\MegaCash;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MegaCashTest extends KernelTestCase
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $serializer;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $container = self::$container;
        $this->serializer = $container->get('serializer');
    }

    public function testDetails()
    {
        $data = [
            "user_id" => 88,
            "details" => "Transaction number oneeeeeeeeeeeeee",
            "receiver_account" => "12345",
            "receiver_name" => "Name Surname",
            "amount" => 20.00,
            "currency" => "usd"
        ];
        $megaCash = new MegaCash(json_encode($data), $this->serializer, $this->entityManager);

        $megaCash->initialise();

        $transaction = $megaCash->getTransaction();

        $this->assertEquals($transaction->getDetails(), substr($data['details'], 0, 20));
        $this->assertGreaterThan(0, $transaction->getFeeAmount());
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