<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Import\ProjectScreenshotResult;
use App\Message\TakeProjectScreenshot;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Panther\Client;

class TakeProjectScreenshotHandler implements MessageHandlerInterface
{
    public function __construct(private string $screenshotPath)
    {
    }

    public function __invoke(TakeProjectScreenshot $takeProjectScreenshot): ProjectScreenshotResult
    {
        $project = $takeProjectScreenshot->getProject();

        if ($project->getUrl() === null) {
            return new ProjectScreenshotResult(false);
        }

        if (!file_exists($this->screenshotPath) && !mkdir($this->screenshotPath, 0777, true)) {
            throw new \RuntimeException('Unable to create screenshot path: ' . $this->screenshotPath);
        }

        $path = $this->screenshotPath . '/' . $project->getAlias() . '.png';

        $client = Client::createFirefoxClient();
        $client->request('GET', $project->getUrl());
        $client->wait(5);
        $client->takeScreenshot($path);

        return new ProjectScreenshotResult(true, $path);
    }
}
