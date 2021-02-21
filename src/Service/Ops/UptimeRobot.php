<?php

declare(strict_types=1);

namespace App\Service\Ops;

use App\Service\ServiceInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class UptimeRobot implements ServiceInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function fetch(array $credentials): void
    {
        $http = new Client([
            'base_uri' => 'https://api.uptimerobot.com/v2/',
            'timeout' => 5,
            'read_timeout' => 5,
            'connect_timeout' => 5,
        ]);

        $resp = $http->post('getMonitors', [
            'form_params' => [
                'api_key' => $credentials['api_key'],
                'format' => 'json',
                'logs' => 1,
            ],
        ]);

        dd((string)$resp->getBody());

        // do things
    }
}
