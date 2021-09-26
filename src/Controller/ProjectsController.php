<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectsController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository)
    {
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
        return $this->render('project.html.twig', [
            'project' => $project,
        ]);
    }
}
