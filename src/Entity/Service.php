<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="services")
 */
class Service
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;
    /**
     * @var array<string,string>
     * @ORM\Column(type="json", options={"jsonb": true})
     */
    private array $credentials;
    /**
     * @ORM\Column(type="text")
     */
    private string $className;
    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private bool $enabled = true;

    /**
     * @param array<string,string> $credentials
     */
    public function __construct(string $name, string $className, array $credentials = [])
    {
        $this->id = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->className = $className;
        $this->credentials = $credentials;
    }

    /**
     * @return array<string,string>
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
