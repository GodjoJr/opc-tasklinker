<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\ProjectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class IndexController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'app_index')]
    public function index(ProjectsRepository $projectsRepository): Response
    {

        $all_projects = $projectsRepository->findAll();

        $projects = [];

        foreach ($all_projects as $project) {
            if (
                !$project->isArchived()
                && (
                    $project->getMembers()->contains($this->getUser())
                    || $this->isGranted('ROLE_MANAGER')
                    || $this->isGranted('ROLE_ADMIN')
                )
            ) {
                $projects[] = $project;
            }

        }
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'projects' => $projects,
        ]);
    }
}
