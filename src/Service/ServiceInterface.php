<?php

declare(strict_types=1);

namespace App\Service;

interface ServiceInterface
{
    /**
     * @param array<string,string> $credentials
     */
    public function fetch(array $credentials): void;
}