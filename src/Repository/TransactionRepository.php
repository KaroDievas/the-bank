<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function getAllAsArray() : array
    {
        return $this->createQueryBuilder('c')->getQuery()->getArrayResult();
    }

    public function getTotalAmountByUserId($userId)
    {
        return $this->createQueryBuilder('c')->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->select('SUM(c.amount) as totalAmount ')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
