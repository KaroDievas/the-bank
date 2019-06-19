<?php
declare(strict_types=1);

namespace App\Providers;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionProviderFactory
{

    private $request;
    private $serializer;
    private $em;

    public function __construct(RequestStack $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->request = $request;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @return BaseTransactionProvider
     * @throws \Exception
     */
    public function getProvider(): BaseTransactionProvider
    {
        $rawData = $this->request->getCurrentRequest()->getContent();
        $data = json_decode($rawData, true);
        if (empty($data) || empty($data['currency'])) {
            throw new \Exception("Please provide currency");
        }

        switch ($data['currency']) {
            case 'eur':
                return new MegaCash($rawData, $this->serializer, $this->em);
            default:
                return new SuperMoney($rawData, $this->serializer, $this->em);
        }
    }
}
