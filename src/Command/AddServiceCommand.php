<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use App\Service\ServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddServiceCommand extends Command
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     * @var string
     */
    protected static $defaultName = 'services:add';

    private ServiceRepository $serviceRepository;
    /** @var iterable<ServiceInterface> */
    private iterable $availableServices;

    /**
     * @param iterable<ServiceInterface> $availableServices
     */
    public function __construct(ServiceRepository $serviceRepository, iterable $availableServices)
    {
        parent::__construct();

        $this->serviceRepository = $serviceRepository;
        $this->availableServices = $availableServices;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Name of the service');

        $serviceClasses = [];
        foreach ($this->availableServices as $availableService) {
            $serviceClasses[] = get_class($availableService);
        }
        sort($serviceClasses);

        $className = $io->choice('Service class', $serviceClasses);

        $credentials = json_decode($io->ask('Credentials (JSON)'), true);

        $service = new Service($name, $className, $credentials);
        $this->serviceRepository->create($service);

        return self::SUCCESS;
    }
}
