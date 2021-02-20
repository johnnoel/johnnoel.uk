<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    /**
     * @return array<Service>
     */
    public function getAllEnabled(): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->where($qb->expr()->eq('s.enabled', ':enabled'))
            ->setParameters([
                'enabled' => true,
            ])
        ;

        return $qb->getQuery()->getResult();
    }

    public function create(Service $service): void
    {
        $this->_em->persist($service);
        $this->_em->flush();
    }
}