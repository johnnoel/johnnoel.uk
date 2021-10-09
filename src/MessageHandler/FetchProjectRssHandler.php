<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\FetchProjectRss;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SimpleXMLElement;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class FetchProjectRssHandler implements MessageHandlerInterface
{
    public function __construct(private CacheInterface $cache)
    {
    }

    public function __invoke(FetchProjectRss $fetchProjectRss): ?SimpleXMLElement
    {
        $project = $fetchProjectRss->getProject();

        if ($project->getRssUrl() === null) {
            return null;
        }

        $key = 'rss.' . $project->getAlias();

        $cacheItem = $this->cache->get($key, function (ItemInterface $item) use ($project): string {
            $item->expiresAfter(24 * 60 * 60);

            $http = new Client([
                'timeout' => 5,
                'read_timeout' => 5,
                'connect_timeout' => 5,
            ]);

            $resp = null;

            try {
                $resp = $http->get($project->getRssUrl());
            } catch (GuzzleException) {
            }

            return ($resp === null) ? '' : (string)$resp->getBody();
        });

        return new SimpleXMLElement($cacheItem);
    }
}
