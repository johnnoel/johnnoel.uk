<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\FetchProjectCodeCommits;
use Github\Api\Repo;
use Github\Client;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class FetchProjectCodeCommitsHandler implements MessageHandlerInterface
{
    public function __construct(private Client $githubClient, private CacheInterface $cache)
    {
    }

    /**
     * @return array<string,mixed>
     */
    public function __invoke(FetchProjectCodeCommits $fetchProjectCodeCommits): array
    {
        $project = $fetchProjectCodeCommits->getProject();

        if ($project->getCodeUrl() === null) {
            return [];
        }

        $key = 'code_commits.' . $project->getAlias();

        return $this->cache->get($key, function (CacheItem $item) use ($project): array {
            $item->expiresAfter(24 * 60 * 60);

            [ $username, $repository ] = $this->getGithubUsernameAndRepoName($project->getCodeUrl());

            /** @var Repo $repoApi */
            $repoApi = $this->githubClient->api('repo');
            $repo = $repoApi->show($username, $repository);

            return $repoApi->commits()->all($username, $repository, [ 'sha' => $repo['default_branch'] ]);
        });
    }

    /**
     * @return array{string,string}
     */
    private function getGithubUsernameAndRepoName(string $url): array
    {
        $urlPath = parse_url($url, PHP_URL_PATH);
        [ $username, $repo ] = explode('/', trim($urlPath, '/'), 2);

        // todo check to see if the repo ends in .git or if it has any other parts

        return [ $username, $repo ];
    }
}
