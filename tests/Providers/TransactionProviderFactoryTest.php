<?php

namespace App\Tests\Providers;

use App\Providers\MegaCash;
use App\Providers\SuperMoney;
use App\Providers\TransactionProviderFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TransactionProviderFactoryTest extends KernelTestCase
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

    public function testIsMegaCash()
    {
        $data = [
            "user_id" => 88,
            "details" => "Transaction number oneeeeeeeeeeeeee",
            "receiver_account" => "12345",
            "receiver_name" => "Name Surname",
            "amount" => 20.00,
            "currency" => "usd"
        ];

        $request = new Request([], [], [], [], [], [], json_encode($data, true));

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $provider = new TransactionProviderFactory($requestStack, $this->serializer, $this->entityManager);
        $transactionProvider = $provider->getProvider();
        $this->assertInstanceOf(SuperMoney::class, $transactionProvider);
    }

    /**
     * @throws \Exception
     */
    public function testEmptyCurrency()
    {
        $request = new Request([], [], [], [], [], [], json_encode(['foo' => 'bar'], true));

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $provider = new TransactionProviderFactory($requestStack, $this->serializer, $this->entityManager);
        $this->expectException(\Exception::class);
        $provider->getProvider();

    }

    public function testIsSuperMoney()
    {
        $data = [
            "user_id" => 88,
            "details" => "Transaction number oneeeeeeeeeeeeee",
            "receiver_account" => "12345",
            "receiver_name" => "Name Surname",
            "amount" => 20.00,
            "currency" => "eur"
        ];

        $request = new Request([], [], [], [], [], [], json_encode($data, true));

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $provider = new TransactionProviderFactory($requestStack, $this->serializer, $this->entityManager);
        $transactionProvider = $provider->getProvider();
        $this->assertInstanceOf(MegaCash::class, $transactionProvider);
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