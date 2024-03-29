<?php

declare(strict_types=1);

namespace Deployer;

use Symfony\Component\Console\Input\InputArgument;

argument('artifact_url', InputArgument::REQUIRED, 'The URL to get build artifacts from');
argument('circleci_token', InputArgument::REQUIRED, 'The personal Circle CI API token to use');

require 'recipe/common.php';

set('application', 'johnnoel.uk');
set('repository', 'git@github.com:johnnoel/johnnoel.uk.git');
set('git_tty', true);
set('writable_dirs', [ 'var/cache' ]);
set('allow_anonymous_stats', false);
set('branch', 'main');
set('default_stage', 'production');

host('67.207.69.0')
    ->stage('production')
    ->user('johnnoel-uk')
    ->forwardAgent(false)
    ->set('deploy_path', '~/www');

task('deploy:update_code', function () {
    $artifactUrl = input()->getArgument('artifact_url');
    $circleCiToken = input()->getArgument('circleci_token');

    $header = escapeshellarg('Circle-Token: ' . $circleCiToken);
    $url = escapeshellarg($artifactUrl);

    $jsonRaw = run('curl -sH ' . $header . ' ' . $url);
    $json = json_decode($jsonRaw, true);

    if (!is_array($json) || count($json) === 0) {
        throw new \InvalidArgumentException('Invalid JSON returned: ' . $jsonRaw);
    }

    $url = escapeshellarg($json[0]['url'] . '?' . http_build_query([
        'circle-token' => $circleCiToken,
    ]));

    run('wget -qO johnnoel-uk.tar.bz2 ' . $url);
    run('tar xjf johnnoel-uk.tar.bz2 -C {{release_path}}');
});

after('deploy:failed', 'deploy:unlock');

desc('Deploy project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');
