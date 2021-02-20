<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ServiceRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Fetcher implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ServiceRepository $serviceRepository;
    /** @var iterable<ServiceInterface> */
    private iterable $services;

    /**
     * @param iterable<ServiceInterface> $services
     */
    public function __construct(ServiceRepository $serviceRepository, iterable $services)
    {
        $this->serviceRepository = $serviceRepository;
        $this->services = $services;
        $this->logger = new NullLogger();
    }

    public function fetch(): void
    {
        $enabledServices = $this->serviceRepository->getAllEnabled();
        $classMap = [];

        foreach ($this->services as $service) {
            $classMap[get_class($service)] = $service;
        }

        foreach ($enabledServices as $enabledService) {
            if (!array_key_exists($enabledService->getClassName(), $classMap)) {
                $this->logger->error('Unable to find service for class ' . $enabledService->getClassName());
                continue;
            }

            $classMap[$enabledService->getClassName()]->fetch($enabledService->getCredentials());
        }
    }
}