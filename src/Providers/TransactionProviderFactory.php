<?php

namespace App\Providers;

class TransactionProviderFactory
{
    public static function createTransactionProviderByCurrency($currency)
    {
        if (empty($currency)) {
            throw new \Exception('Undefined currency');
        }

        switch ($currency) {
            case 'eur':
                return new MegaCash();
            default:
                return new SuperMoney();
        }
    }
}
