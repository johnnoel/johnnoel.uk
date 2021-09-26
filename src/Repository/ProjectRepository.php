<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @return array<Project>
     */
    public function findAllProjects(): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->orderBy('p.name', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findProject(string $alias): ?Project
    {
        $qb = $this->createQueryBuilder('p');
        $qb->where($qb->expr()->eq('p.alias', ':alias'))
            ->setParameters([
                'alias' => $alias,
            ])
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
