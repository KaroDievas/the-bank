<?php

namespace App\Security\Voter;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TransactionVoter extends Voter
{
    private $em;

    const MAX_TRANSACTION_COUNT_PER_HOUR = 10;
    const MAX_AMOUNT_PER_CURRENCY = 1000;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['CREATE']);
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
            case 'CREATE':
                $totalTransactions = $this->em->getRepository(Transaction::class)->getTotalTransactionsCountPerHourByUserId($subject['user_id']);
                // Check total transaction count for last hour
                if ($totalTransactions == self::MAX_TRANSACTION_COUNT_PER_HOUR) {
                    return false;
                }

                $totalAmountPerCurrency = $this->em->getRepository(Transaction::class)->getTotalAmountByUserAndByCurrency($subject['user_id'], $subject['currency']);
                // check total amount for particular user and currency
                // There is a bug that in this case new transaction fee is not applied
                if ($totalAmountPerCurrency + $subject['amount'] >= self::MAX_AMOUNT_PER_CURRENCY) {
                    return false;
                }
                break;
        }

        return true;
    }
}
