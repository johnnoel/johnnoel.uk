<?php

declare(strict_types=1);

namespace App\Import;

use App\Entity\Project;

class ProjectImportResult
{
    public function __construct(private Project $project, private bool $new)
    {
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function isNew(): bool
    {
        return $this->new;
    }
}
