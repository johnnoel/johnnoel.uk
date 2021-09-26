<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\Project;

class FetchProjectCodeCommits
{
    public function __construct(private Project $project)
    {
    }

    public function getProject(): Project
    {
        return $this->project;
    }
}
