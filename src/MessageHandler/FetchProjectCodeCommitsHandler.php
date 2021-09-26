<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\FetchProjectCodeCommits;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class FetchProjectCodeCommitsHandler implements MessageHandlerInterface
{
    public function __construct(private CacheInterface $cache)
    {
    }

    public function __invoke(FetchProjectCodeCommits $fetchProjectCodeCommits): void
    {
        $project = $fetchProjectCodeCommits->getProject();

        $key = 'code_commits.' . $project->getAlias();

        $commits = $this->cache->get($key, function (CacheItem $item): string {
            $item->expiresAfter(24 * 60 * 60);

            return '';
        });
    }
}
