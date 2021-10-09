<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Message\FetchProjectCodeCommits;
use App\Message\FetchProjectRss;
use App\Repository\ProjectRepository;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    use HandleTrait;

    public function __construct(
        private ProjectRepository $projectRepository,
        private SerializerInterface $serializer,
        MessageBusInterface $messageBus
    ) {
        $this->messageBus = $messageBus;
    }

    #[Route(
        path: '/{_locale}/projects.{_format}',
        name: 'projects',
        requirements: [ '_format' => '|json' ],
        defaults: [ '_format' => 'html' ],
        methods: [ 'GET' ],
    )]
    public function index(Request $request): Response
    {
        $projects = $this->projectRepository->findAllProjects();

        if ($request->getRequestFormat() === 'json') {
            return new JsonResponse($this->serializer->serialize($projects, 'json'), Response::HTTP_OK, [], true);
        }

        return $this->render('projects.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route(
        path: '/{_locale}/projects/{alias}.{_format}',
        name: 'project',
        requirements: [ '_format' => '|json' ],
        defaults: [ '_format' => 'html' ],
        methods: [ 'GET' ],
    )]
    #[Entity('project', expr: 'repository.findProject(alias)')]
    public function project(Project $project, Request $request): Response
    {
        if (file_exists(__DIR__ . '/../../public/projects/' . $project->getAlias() . '.png')) {
            $project->screenshot = '/projects/' . $project->getAlias() . '.png';
        }

        if ($request->getRequestFormat() === 'json') {
            return new JsonResponse($this->serializer->serialize($project, 'json'), Response::HTTP_OK, [], true);
        }

        return $this->render('project.html.twig', [
            'project' => $project,
        ]);
    }

    public function rssFragment(Project $project): Response
    {
        if ($project->getRssUrl() === null || $project->getRssUrl() === '') {
            return new Response('');
        }

        $rss = $this->handle(new FetchProjectRss($project));

        return $this->render('_project_rss.html.twig', [
            'rss' => $rss,
        ]);
    }

    public function codeFragment(Project $project): Response
    {
        if ($project->getCodeUrl() === null || $project->getCodeUrl() === '') {
            return new Response('');
        }

        $commits = $this->handle(new FetchProjectCodeCommits($project));

        return $this->render('_project_code_commits.html.twig', [
            'commits' => $commits,
        ]);
    }
}
