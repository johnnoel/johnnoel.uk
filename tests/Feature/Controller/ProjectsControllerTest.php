<?php

declare(strict_types=1);

namespace App\Tests\Feature\Controller;

use App\Tests\Factory\ProjectFactory;
use App\Tests\Factory\StatusFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;

class ProjectsControllerTest extends WebTestCase
{
    use Factories;

    public function testIndexExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/en/projects');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testIndexJsonExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/en/projects.json');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
    }

    public function testIndexExistsWithProjects(): void
    {
        $client = static::createClient();

        $status = StatusFactory::createOne();
        ProjectFactory::createMany(5, [ 'status' => $status ]);

        $client->request('GET', '/en/projects');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testProjectExists(): void
    {
        $client = static::createClient();
        $status = StatusFactory::createOne();
        $project = ProjectFactory::createOne([ 'status' => $status ]);

        $client->request('GET', '/en/projects/' . $project->getAlias());

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testProjectJsonExists(): void
    {
        $client = static::createClient();
        $status = StatusFactory::createOne();
        $project = ProjectFactory::createOne([ 'status' => $status ]);
        $client->request('GET', '/en/projects/' . $project->getAlias() . '.json');

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
    }
}
