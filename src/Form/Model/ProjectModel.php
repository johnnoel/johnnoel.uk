<?php

declare(strict_types=1);

namespace App\Form\Model;

use App\Entity\Status;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectModel
{
    #[Assert\NotBlank]
    public ?string $name = null;

    #[Assert\NotBlank]
    public ?string $alias = null;

    #[Assert\NotBlank]
    #[Assert\Url]
    public ?string $url = null;

    public ?string $description = null;

    #[Assert\Url]
    public ?string $rssUrl = null;

    #[Assert\Url]
    public ?string $codeUrl = null;

    #[Assert\Regex('/^\d+$/')]
    public ?string $uptimeRobotId = null;

    #[Assert\NotBlank]
    public ?Status $status = null;
}
