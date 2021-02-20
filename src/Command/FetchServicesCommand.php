<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Fetcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class FetchServicesCommand extends Command
{
    protected static $defaultName = 'services:fetch';

    private Fetcher $fetcher;

    public function __construct(Fetcher $fetcher)
    {
        parent::__construct();

        $this->fetcher = $fetcher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $logger = new ConsoleLogger($output);
        $this->fetcher->setLogger($logger);

        $this->fetcher->fetch();

        return self::SUCCESS;
    }
}