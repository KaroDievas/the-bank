<?php

namespace App\Command;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionProcessorCommand extends Command
{
    protected static $defaultName = 'app:process-transactions';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:process-transactions')
            ->setDescription('Transaction processor!');
    }

    /**
     * Processes pending and signed transactions
     * There is 5 seconds sleep after process
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->info('Starting');
        while (true) {
            $pendingSignedTransactions = $this->em->getRepository(Transaction::class)->getTransactionsByStatusSignedPending();
            foreach ($pendingSignedTransactions as $transaction) {
                $this->logger->info(sprintf('Processing transaction with id = %s', $transaction->getId()));
                $transaction->setStatus('COMPLETED');
                $this->em->persist($transaction);
                $this->em->flush();
                $this->logger->info(sprintf('Processed transaction with id = %s', $transaction->getId()));
            }
            sleep(5);
        }
        $this->logger->info('End');
    }
}