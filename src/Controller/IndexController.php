<?php

namespace App\Controller;

use App\Repository\ProjectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProjectsRepository $projectsRepository): Response
    {

        $all_projects = $projectsRepository->findAll();

        $projects = [];

        foreach ($all_projects as $project) {
            if (!$project->isArchived()) {
                $projects[] = $project;
            }
        }
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'projects' => $projects
        ]);
    }
}
