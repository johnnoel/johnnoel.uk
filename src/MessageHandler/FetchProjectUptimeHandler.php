<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\FetchProjectUptime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class FetchProjectUptimeHandler implements MessageHandlerInterface
{
    private const UPTIME_ROBOT_API_BASE_URI = 'https://api.uptimerobot.com/v2/';

    public function __construct(private string $uptimeRobotApiKey, private CacheInterface $cache)
    {
    }

    public function __invoke(FetchProjectUptime $fetchProjectUptime): array
    {
        $project = $fetchProjectUptime->getProject();

        if ($project->getUptimeRobotId() === null) {
            return [];
        }

        $key = 'uptime.' . $project->getAlias();

        return $this->cache->get($key, function (ItemInterface $item) use ($project): array {
            $item->expiresAfter(24 * 60 * 60);

            $http = new Client([
                'timeout' => 10,
                'read_timeout' => 10,
                'connect_timeout' => 10,
                'base_uri' => self::UPTIME_ROBOT_API_BASE_URI,
            ]);

            $resp = null;

            try {
                $resp = $http->post('getMonitors?api_key=' . $this->uptimeRobotApiKey, [
                    'form_params' => [
                        'monitors' => $project->getUptimeRobotId(),
                        'logs' => 1,
                        'response_times' => 1,
                    ],
                ]);
            } catch (GuzzleException $e) {
                return [];
            }

            $json = json_decode((string)$resp->getBody(), true);

            if (!is_array($json)) {
                return [];
            }

            return $json;
        });
    }
}
