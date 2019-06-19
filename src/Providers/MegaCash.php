<?php
declare(strict_types = 1);

namespace App\Providers;

class MegaCash extends BaseTransactionProvider
{
    /**
     * Applies custom rules for transaction details
     */
    public function setDetails() : void
    {
        $this->transaction->setDetails(substr($this->transaction->getDetails(), 0, 20));
    }
}
