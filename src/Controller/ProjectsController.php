<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Message\FetchProjectCodeCommits;
use App\Message\FetchProjectRss;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    use HandleTrait;

    public function __construct(private ProjectRepository $projectRepository, MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route(path: '/{_locale}/projects', name: 'projects', methods: [ 'GET' ])]
    public function index(): Response
    {
        $projects = $this->projectRepository->findAllProjects();

        return $this->render('projects.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route(path: '/{_locale}/projects/{alias}', name: 'project', methods: [ 'GET' ])]
    #[Entity('project', expr: 'repository.findProject(alias)')]
    public function project(Project $project): Response
    {
        if (file_exists(__DIR__ . '/../../public/projects/' . $project->getAlias() . '.png')) {
            $project->screenshot = '/projects/' . $project->getAlias() . '.png';
        }

        return $this->render('project.html.twig', [
            'project' => $project,
        ]);
    }

    public function rssFragment(Project $project): Response
    {
        $rss = $this->handle(new FetchProjectRss($project));

        return $this->render('_project_rss.html.twig', [
            'rss' => $rss,
        ]);
    }

    public function codeFragment(Project $project): Response
    {
        $commits = $this->handle(new FetchProjectCodeCommits($project));

        return $this->render('_project_code_commits.html.twig', [
            'commits' => $commits,
        ]);
    }
}
