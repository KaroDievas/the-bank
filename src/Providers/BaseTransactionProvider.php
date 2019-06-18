<?php
/**
 * Created by IntelliJ IDEA.
 * User: KD
 * Date: 2019-06-18
 * Time: 23:06
 */

namespace App\Providers;


use App\Entity\Transaction;

class BaseTransactionProvider
{
    protected $transaction;

    public function __construct()
    {
        $this->transaction = new Transaction();
    }

    public function setDetails($details)
    {
        $this->transaction->setDetails($details);
    }
}