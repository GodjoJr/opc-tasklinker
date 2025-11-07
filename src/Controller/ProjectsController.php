<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Entity\Tasks;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects', name: 'app_projects_')]
final class ProjectsController extends AbstractController
{
    #[Route('/show/{id}', name: 'show')]
    public function show(int $id, EntityManagerInterface $em): Response
    {

        $project = $em->getRepository(Projects::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Projet' . $id . 'non trouvé.');
        }

        $all_tasks = $em->getRepository(Tasks::class)->findBy(['projects' => $id]);

        $tasks = [];

        foreach ($all_tasks as $task) {
            switch ($task->getStatus()) {
                case 'To Do':
                    $tasks['todo'][] = $task;
                    break;
                case 'Doing':
                    $tasks['doing'][] = $task;
                    break;
                case 'Done':
                    $tasks['done'][] = $task;
                    break;
                default:
                    throw $this->createNotFoundException('Tâche ' . $task . ' non classée.');
            }
        }

        return $this->render('projects/show.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {

        $project = new Projects();
        $project->setIsArchived(false);
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();
            return $this->redirectToRoute('app_index');
        }

        return $this->render('projects/add.html.twig', [
            'controller_name' => 'ProjectsController',
            'form' => $form,
        ]);
    }


    #[Route('/edit', name: 'edit')]
    public function edit(): Response
    {
        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
        ]);
    }

    #[Route('/archive/{id}', name: 'archive')]
    public function archive(int $id, EntityManagerInterface $em): Response
    {

        $project = $em->getRepository(Projects::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException('Projet' . $id . 'non rencontré.');
        }

        $project->setIsArchived(true);
        $em->persist($project);
        $em->flush();

        return $this->redirectToRoute('app_index');
    }


}
