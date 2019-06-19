<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionProcessorCommand extends Command
{
    protected static $defaultName = 'app:process-transactions';

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $em = $this->
    }
}