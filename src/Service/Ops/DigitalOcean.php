<?php

declare(strict_types=1);

namespace App\Service\Ops;

use App\Service\ServiceInterface;

class DigitalOcean implements ServiceInterface
{
    /**
     * @inheritDoc
     */
    public function fetch(array $credentials): void
    {
        // TODO: Implement fetch() method.
    }
}
