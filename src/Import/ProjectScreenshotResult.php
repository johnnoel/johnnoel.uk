<?php

declare(strict_types=1);

namespace App\Import;

class ProjectScreenshotResult
{
    public function __construct(private bool $successful, private ?string $screenshotPath = null)
    {
    }

    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    public function getScreenshotPath(): ?string
    {
        return $this->screenshotPath;
    }
}
