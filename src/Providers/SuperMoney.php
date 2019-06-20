<?php
declare(strict_types=1);

namespace App\Providers;

class SuperMoney extends BaseTransactionProvider
{
    /**
     * Applies custom rules for transaction details
     */
    public function setDetails(): void
    {
        $this->transaction->setDetails(sprintf("%s%s", $this->transaction->getDetails(), rand(1, 99999999)));
    }
}
