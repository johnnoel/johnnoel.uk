<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ORM\Table(name: 'project_status')]
class Status
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string', length: 32)]
    private string $name;

    #[ORM\Column(type: 'string', length: 32, unique: true)]
    private string $alias;

    public function __construct(string $name, string $alias)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->alias = $alias;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
