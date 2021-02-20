<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="api_responses")
 */
class ApiResponse
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;
    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $dateTime;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private Service $service;
    /**
     * @var array<mixed>
     * @ORM\Column(type="json", options={"jsonb": true})
     */
    private array $response;

    /**
     * @param array<mixed> $response
     */
    public function __construct(Service $service, array $response)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->dateTime = new DateTimeImmutable('now');
        $this->service = $service;
        $this->response = $response;
    }

    /**
     * @return array<mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}