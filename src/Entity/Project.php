<?php

declare(strict_types=1);

namespace App\Entity;

use App\Form\Model\ProjectModel;
use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $alias;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $url;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $rssUrl;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $codeUrl;

    #[ORM\ManyToOne(targetEntity: Status::class)]
    #[ORM\JoinColumn(name: 'status_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private Status $status;

    public function __construct(
        string $name,
        string $alias,
        ?string $url,
        string $description,
        Status $status,
        ?string $rssUrl = null,
        ?string $codeUrl = null
    ) {
        $this->id = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->alias = $alias;
        $this->url = $url;
        $this->description = $description;
        $this->status = $status;
        $this->rssUrl = $rssUrl;
        $this->codeUrl = $codeUrl;
    }

    public static function createFromModel(ProjectModel $model): self
    {
        if (
            $model->name === null ||
            $model->alias === null ||
            $model->url === null ||
            $model->description === null ||
            $model->status === null
        ) {
            throw new \InvalidArgumentException();
        };

        return new self(
            $model->name,
            $model->alias,
            $model->url,
            $model->description,
            $model->status,
            $model->rssUrl
        );
    }

    public function updateFromModel(ProjectModel $model): void
    {
        if (
            $model->name === null ||
            $model->alias === null ||
            $model->url === null ||
            $model->description === null ||
            $model->status === null
        ) {
            throw new \InvalidArgumentException();
        };

        $this->name = $model->name;
        $this->alias = $model->alias;
        $this->url = $model->url;
        $this->description = $model->description;
        $this->status = $model->status;
        $this->rssUrl = $model->rssUrl;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRssUrl(): ?string
    {
        return $this->rssUrl;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
